<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\details;

use \spectrum\core\details\PhpErrorInterface;

class phpError extends \spectrum\core\plugins\reports\drivers\text\components\component {
	static public function getContent(PhpErrorInterface $details) {
		return
			static::getContentForErrorLevel($details) . static::getOutputNewline() .
			static::getContentForErrorMessage($details) . static::getOutputNewline() .
			static::getContentForSource($details);
	}

	static protected function getContentForErrorLevel(PhpErrorInterface $details) {
		$errorLevel = $details->getErrorLevel();
		return static::translate('Error level') . ': ' . $errorLevel . ' (' . static::getErrorLevelConstantNameByValue($errorLevel) . ')';
	}
	
	static protected function getErrorLevelConstantNameByValue($constantValue) {
		$constants = get_defined_constants(true);
		foreach ($constants['Core'] as $name => $value) {
			if ($value === $constantValue) {
				return $name;
			}
		}
		
		return null;
	}
	
	static protected function getContentForErrorMessage(PhpErrorInterface $details) {
		return static::translate('Error message') . ': "' . $details->getErrorMessage() . '"';
	}
	
	static protected function getContentForSource(PhpErrorInterface $details) {
		return
			static::translate('Source') . ': ' .
			static::translate('file') . ' ' . '"' . static::convertToOutputCharset($details->getFile(), 'utf-8') . '", ' .
			static::translate('line') . ' ' . $details->getLine();
	}
}