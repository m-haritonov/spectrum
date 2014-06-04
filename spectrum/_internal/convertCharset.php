<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;

function convertCharset($string, $inputCharset = null, $outputCharset = null)
{
	if ($inputCharset === null)
		$inputCharset = config::getInputCharset();
	
	if ($outputCharset === null)
		$outputCharset = config::getOutputCharset();
	
	if ((string) $inputCharset === (string) $outputCharset)
		return $string;
	else
		return mb_convert_encoding($string, $outputCharset, $inputCharset);
}