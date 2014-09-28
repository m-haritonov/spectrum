<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code\variables;

class arrayVar extends \spectrum\core\plugins\reports\drivers\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-code-variables-array { display: inline-block; vertical-align: text-top; border-radius: 4px; background: rgba(255, 255, 255, 0.5); font-size: 12px; }
			.app-code-variables-array>.indention { display: none; }
			.app-code-variables-array>.type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.app-code-variables-array>.app-code-operator.curlyBrace { display: none; }
			.app-code-variables-array>.elements:before { content: "\\007B\\2026\\007D"; color: rgba(0, 0, 0, 0.6); }
			.app-code-variables-array>.elements>.element { display: none; }
			.app-code-variables-array>.elements>.element>.indention { display: inline-block; overflow: hidden; width: 25px; white-space: pre; }
			.app-code-variables-array .app-code-variables-array,
			.app-code-variables-array .app-code-variables-object { display: inline; vertical-align: baseline; background: transparent; }
		
			.app-resultBuffer>.results>.result.expanded .app-code-variables-array>.indention { display: inline-block; overflow: hidden; width: 25px; white-space: pre; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-array>.app-code-operator.curlyBrace { display: inline; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-array>.elements:before { display: none; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-array>.elements>.element { display: block; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @param int $depth
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent(array $variable, $depth, $inputCharset = null) {
		$content = '';
		$content .= '<span class="app-code-variables-array">';
		$content .= static::getContentForType($variable);
		$content .= static::callComponentMethod('code\operator', 'getContent', array('{', 'us-ascii'));
		$content .= static::getContentForElements($variable, $depth, $inputCharset);
		
		if (count($variable)) {
			$content .= str_repeat('<span class="indention">' . static::getHtmlEscapedOutputIndention() . '</span>', $depth); // Indention should be copied to buffer
		}
		
		$content .= static::callComponentMethod('code\operator', 'getContent', array('}', 'us-ascii'));
		$content .= '</span>';
		return $content;
	}

	/**
	 * @return string
	 */
	static protected function getContentForType(array $variable) {
		return
			'<span class="type">' .
				static::translateAndEscapeHtml('array') .
				'<span title="' . static::translateAndEscapeHtml('Elements count') . '">(' . static::escapeHtml(count($variable)) . ')</span>' .
			'</span> ';
	}

	/**
	 * @param int $depth
	 * @param string $inputCharset
	 * @return string
	 */
	static protected function getContentForElements(array $variable, $depth, $inputCharset) {
		$content = '';
		if (count($variable)) {
			$content .= '<span class="elements">';
			foreach ($variable as $key => $value) {
				$content .= static::getContentForElement($key, $value, $depth, $inputCharset);
			}

			$content .= '</span>';
		}
		
		return $content;
	}

	/**
	 * @param mixed $key
	 * @param mixed $value
	 * @param int $depth
	 * @param string $inputCharset
	 * @return string
	 */
	static protected function getContentForElement($key, $value, $depth, $inputCharset) {
		return
			'<span class="element">' .
				// Indention should be copied to buffer
				str_repeat('<span class="indention">' . static::getHtmlEscapedOutputIndention() . '</span>', $depth + 1) .
				'<span class="key">' .
					static::callComponentMethod('code\operator', 'getContent', array('[', 'us-ascii')) .
					static::escapeHtml(static::convertToOutputCharset($key, $inputCharset)) .
					static::callComponentMethod('code\operator', 'getContent', array(']', 'us-ascii')) .
				'</span> ' .
				static::callComponentMethod('code\operator', 'getContent', array('=>', 'us-ascii')) . ' ' .
				static::callComponentMethod('code\variable', 'getContent', array($value, $depth + 1, $inputCharset)) .
			'</span>';
	}
}