<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code;

class method extends \spectrum\core\plugins\reports\drivers\html\components\component {
	/**
	 * @param string $methodName String in "us-ascii" charset
	 * @param array $arguments Data in input charset
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