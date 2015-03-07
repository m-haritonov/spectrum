<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private\reports\text\components\code;

class method extends \spectrum\_private\reports\text\components\component {
	/**
	 * @param string $methodName String in "US-ASCII" charset
	 * @param array $arguments Data in input charset
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($methodName, array $arguments, $inputCharset = null) {
		return
			static::convertToOutputCharset($methodName, $inputCharset) .
			static::callComponentMethod('code\operator', 'getContent', array('(', 'us-ascii')) .
			static::getContentForArguments($arguments, $inputCharset) .
			static::callComponentMethod('code\operator', 'getContent', array(')', 'us-ascii'));
	}

	/**
	 * @param string $inputCharset
	 * @return string
	 */
	static public function getContentForArguments(array $arguments, $inputCharset) {
		$content = '';
		
		end($arguments);
		$lastKey = key($arguments);
		
		foreach ($arguments as $key => $argument) {
			$content .= static::callComponentMethod('code\variable', 'getContent', array($argument, 0, $inputCharset));
			
			if ($key !== $lastKey) {
				$content .= ', ';
			}
		}

		return $content;
	}
}