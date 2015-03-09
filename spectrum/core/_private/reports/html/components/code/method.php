<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\html\components\code;

class method extends \spectrum\core\_private\reports\html\components\component {
	/**
	 * @param string $methodName String in "US-ASCII" charset
	 * @param array $arguments Data in input charset
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($methodName, array $arguments, $inputCharset = null) {
		return
			'<span class="app-code-method">' .
				'<span class="methodName">' . static::escapeHtml(static::convertToOutputCharset($methodName, $inputCharset)) . '</span>' .
				static::callComponentMethod('code\operator', 'getContent', array('(', 'us-ascii')) .
				'<span class="arguments">' . static::getContentForArguments($arguments, $inputCharset) . '</span>' .
				static::callComponentMethod('code\operator', 'getContent', array(')', 'us-ascii')) .
			'</span>';
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