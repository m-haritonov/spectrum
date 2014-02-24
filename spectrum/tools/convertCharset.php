<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tools;

use spectrum\config;

function convertCharset($string, $inputCharset = null, $outputCharset = null)
{
	if ($inputCharset === null)
		$inputCharset = config::getInputCharset();
	
	if ($outputCharset === null)
		$outputCharset = config::getOutputCharset();
	
	if ($inputCharset == $outputCharset)
		return $string;
	else
		return mb_convert_encoding($string, $outputCharset, $inputCharset);
}