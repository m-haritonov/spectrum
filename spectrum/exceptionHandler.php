<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

set_exception_handler(function(\Exception $exception)
{
	$inputCharset = \spectrum\config::getInputCharset();
	$outputCharset = \spectrum\config::getOutputCharset();
	$outputNewline = \spectrum\config::getOutputNewline();
	
	$exceptionClass = mb_convert_encoding(get_class($exception), $outputCharset, $inputCharset);
	$exceptionMessage = mb_convert_encoding($exception->getMessage(), $outputCharset, $inputCharset);
	$exceptionCode = mb_convert_encoding($exception->getCode(), $outputCharset, $inputCharset);
	$exceptionFile = mb_convert_encoding($exception->getFile(), $outputCharset, 'utf-8'); // Filenames are come in OS charset (conceivably in "utf-8")
	$exceptionLine = mb_convert_encoding($exception->getLine(), $outputCharset, $inputCharset);
	$exceptionTrace = mb_convert_encoding($exception->getTraceAsString(), $outputCharset, 'utf-8');
	
	if ((string) \spectrum\config::getOutputFormat() === 'html')
	{
		$outputNewline = '<br />' . $outputNewline;
		
		$exceptionClass = htmlspecialchars($exceptionClass, ENT_QUOTES, 'iso-8859-1');
		$exceptionMessage = htmlspecialchars($exceptionMessage, ENT_QUOTES, 'iso-8859-1');
		$exceptionCode = htmlspecialchars($exceptionCode, ENT_QUOTES, 'iso-8859-1');
		$exceptionFile = htmlspecialchars($exceptionFile, ENT_QUOTES, 'iso-8859-1');
		$exceptionLine = htmlspecialchars($exceptionLine, ENT_QUOTES, 'iso-8859-1');
		$exceptionTrace = str_replace("\n", $outputNewline, htmlspecialchars($exceptionTrace, ENT_QUOTES, 'iso-8859-1'));
	}
	else
		$exceptionTrace = str_replace("\n", $outputNewline, $exceptionTrace);

	print
		'Fatal error: uncaught exception ' .
		'"\\' . $exceptionClass . '" ' .
		'with message "' . $exceptionMessage . '" ' .
		'and code "' . $exceptionCode . '" ' .
		'thrown in file "' . $exceptionFile . '" ' .
		'on line ' . $exceptionLine . $outputNewline .
		'Stack trace:' . $outputNewline . $exceptionTrace;
});