<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;

use spectrum\config;
use spectrum\Exception;

/**
 * @access private
 * @return string
 */
function getReportClass() {
	$convertLatinCharsToLowerCaseFunction = config::getFunctionReplacement('\spectrum\_internals\convertLatinCharsToLowerCase');
	$outputFormatWithLatinLowerCase = $convertLatinCharsToLowerCaseFunction(config::getOutputFormat());
	
	if ($outputFormatWithLatinLowerCase === 'html') {
		return config::getClassReplacement('\spectrum\_internals\reports\html\driver');
	} else if ($outputFormatWithLatinLowerCase === 'text') {
		return config::getClassReplacement('\spectrum\_internals\reports\text\driver');
	} else {
		throw new Exception('Output format "' . config::getOutputFormat() . '" is not supported');
	}
}