<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\code;

class method extends \spectrum\core\plugins\reports\drivers\text\components\component {
	/**
	 * @param string $methodName String in "us-ascii" charset
	 * @param array $arguments Data in input charset
	 * @return string
	 */
	static public function getContent($methodName, array $arguments, $inputCharset = null) {
		return
			static::convertToOutputCharset($methodName, $inputCharset) .
			static::callComponentMethod('code\operator', 'getContent', array('(', 'us-ascii')) .
			static::getContentForArguments($arguments, $inputCharset) .
			static::callComponentMethod('code\operator', 'getContent', array(')', 'us-ascii'));
	}

	static public function getContentForArguments(array $arguments, $inputCharset) {
		$output = '';
		
		end($arguments);
		$lastKey = key($arguments);
		
		foreach ($arguments as $key => $argument) {
			$output .= static::callComponentMethod('code\variable', 'getContent', array($argument, 0, $inputCharset));
			
			if ($key !== $lastKey) {
				$output .= ', ';
			}
		}

		return $output;
	}
}