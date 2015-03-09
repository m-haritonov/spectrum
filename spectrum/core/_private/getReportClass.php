<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\config;
use spectrum\core\Exception;

/**
 * @access private
 * @return string
 */
function getReportClass() {
	$convertLatinCharsToLowerCaseFunction = config::getFunctionReplacement('\spectrum\core\_private\convertLatinCharsToLowerCase');
	$outputFormatWithLatinLowerCase = $convertLatinCharsToLowerCaseFunction(config::getOutputFormat());
	
	if ($outputFormatWithLatinLowerCase === 'html') {
		return config::getClassReplacement('\spectrum\core\_private\reports\html\driver');
	} else if ($outputFormatWithLatinLowerCase === 'text') {
		return config::getClassReplacement('\spectrum\core\_private\reports\text\driver');
	} else {
		throw new Exception('Output format "' . config::getOutputFormat() . '" is not supported');
	}
}