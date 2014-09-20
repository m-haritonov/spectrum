<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code\variables;

class arrayVar extends \spectrum\core\plugins\reports\drivers\html\components\component {
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-code-variables-array { display: inline-block; vertical-align: text-top; border-radius: 4px; background: rgba(255, 255, 255, 0.5); font-size: 12px; }
			.c-code-variables-array>.indention { display: none; }
			.c-code-variables-array>.type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.c-code-variables-array>.c-code-operator.curlyBrace { display: none; }
			.c-code-variables-array>.elements:before { content: "\\007B\\2026\\007D"; color: rgba(0, 0, 0, 0.6); }
			.c-code-variables-array>.elements>.element { display: none; }
			.c-code-variables-array>.elements>.element>.indention { display: inline-block; overflow: hidden; width: 25px; white-space: pre; }
			.c-code-variables-array .c-code-variables-array,
			.c-code-variables-array .c-code-variables-object { display: inline; vertical-align: baseline; background: transparent; }
		
			.c-resultBuffer>.results>.result.expanded .c-code-variables-array>.indention { display: inline-block; overflow: hidden; width: 25px; white-space: pre; }
			.c-resultBuffer>.results>.result.expanded .c-code-variables-array>.c-code-operator.curlyBrace { display: inline; }
			.c-resultBuffer>.results>.result.expanded .c-code-variables-array>.elements:before { display: none; }
			.c-resultBuffer>.results>.result.expanded .c-code-variables-array>.elements>.element { display: block; }
		/*]]>*/</style>', 2);
	}

	static public function getHtml($variable, $depth, $inputCharset = null) {
		$output = '';
		$output .= '<span class="c-code-variables-array">';
		$output .= static::getHtmlForType($variable);
		$output .= static::callComponentMethod('code\operator', 'getHtml', array('{', 'us-ascii'));
		$output .= static::getHtmlForElements($variable, $depth, $inputCharset);
		
		if (count($variable)) {
			$output .= str_repeat('<span class="indention">' . static::getHtmlEscapedOutputIndention() . '</span>', $depth); // Indention should be copied to buffer
		}
		
		$output .= static::callComponentMethod('code\operator', 'getHtml', array('}', 'us-ascii'));
		$output .= '</span>';
		return $output;
	}
	
	static protected function getHtmlForType($variable) {
		return
			'<span class="type">' .
				static::translateAndEscapeHtml('array') .
				'<span title="' . static::translateAndEscapeHtml('Elements count') . '">(' . static::escapeHtml(count($variable)) . ')</span>' .
			'</span> ';
	}
	
	static protected function getHtmlForElements($variable, $depth, $inputCharset) {
		$output = '';
		if (count($variable)) {
			$output .= '<span class="elements">';
			foreach ($variable as $key => $value)
				$output .= static::getHtmlForElement($key, $value, $depth, $inputCharset);

			$output .= '</span>';
		}
		
		return $output;
	}
	
	static protected function getHtmlForElement($key, $value, $depth, $inputCharset) {
		return
			'<span class="element">' .
				// Indention should be copied to buffer
				str_repeat('<span class="indention">' . static::getHtmlEscapedOutputIndention() . '</span>', $depth + 1) .
				'<span class="key">' .
					static::callComponentMethod('code\operator', 'getHtml', array('[', 'us-ascii')) .
					static::escapeHtml(static::convertToOutputCharset($key, $inputCharset)) .
					static::callComponentMethod('code\operator', 'getHtml', array(']', 'us-ascii')) .
				'</span> ' .
				static::callComponentMethod('code\operator', 'getHtml', array('=>', 'us-ascii')) . ' ' .
				static::callComponentMethod('code\variable', 'getHtml', array($value, $depth + 1, $inputCharset)) .
			'</span>';
	}
}