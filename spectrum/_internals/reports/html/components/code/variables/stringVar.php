<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals\reports\html\components\code\variables;

use spectrum\config;

class stringVar extends \spectrum\_internals\reports\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-code-variables-string { font-size: 12px; }
			.app-code-variables-string .type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.app-code-variables-string .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.app-code-variables-string .quote { color: rgba(0, 0, 0, 0.6); }
		
			.app-resultBuffer>.results>.result.expanded .app-code-variables-string .value { overflow: visible; max-width: none; white-space: pre; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-string .value .char { display: inline-block; overflow: hidden; position: relative; height: 12px; }

			.app-resultBuffer>.results>.result.expanded .app-code-variables-string .value .char.space { width: 8px; height: 10px; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-string .value .char.space:before { content: "\\0020"; position: absolute; bottom: 1px; left: 49%; width: 2px; height: 2px; background: #bbb; }

			.app-resultBuffer>.results>.result.expanded .app-code-variables-string .value .char.tab { width: 15px; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-string .value .char.tab:before { content: "\\21E5"; position: absolute; right: 0; left: 0; text-align: center; color: #aaa; }

			.app-resultBuffer>.results>.result.expanded .app-code-variables-string .value .char.cr { width: 14px; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-string .value .char.cr:before { content: "\\21A9"; position: absolute; bottom: -1px; right: 0; left: 0; text-align: center; color: #aaa; }

			.app-resultBuffer>.results>.result.expanded .app-code-variables-string .value .char.lf { width: 10px; height: 11px; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-string .value .char.lf:before { content: "\\2193"; position: absolute; bottom: 3px; right: 0; left: 0; text-align: center; color: #aaa; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-string .value .char.lf:after { content: "\\0020"; position: absolute; bottom: 2px; right: 2px; left: 2px; border-bottom: 1px solid #bbb; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @param string $variable
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($variable, $inputCharset = null) {
		return
			'<span class="app-code-variables-string">' .
				static::getContentForType($variable, $inputCharset) .
				static::getContentForValue($variable, $inputCharset) .
			'</span>';
	}

	/**
	 * @param string $variable
	 * @param string $inputCharset
	 * @return string
	 */
	static protected function getContentForType($variable, $inputCharset) {
		if ($inputCharset === null) {
			$inputCharset = config::getInputCharset();
		}
		
		return
			'<span class="type">' .
				static::translateAndEscapeHtml('string') .
				'<span title="' . static::translateAndEscapeHtml('String length') . '">' . 
					'(' . 
					static::translateAndEscapeHtml('%count% chars', array('%count%' => static::escapeHtml(mb_strlen($variable, $inputCharset)))) . ', ' .
					static::translateAndEscapeHtml('%count% bytes', array('%count%' => static::escapeHtml(mb_strlen($variable, 'us-ascii')))) .
					')' .
				'</span>' .
			'</span> ';
	}

	/**
	 * @param string $variable
	 * @param string $inputCharset
	 * @return string
	 */
	static protected function getContentForValue($variable, $inputCharset) {
		return
			'<span class="quote open">&quot;</span>' .
				'<span class="value">' . static::highlightSpaces(static::escapeHtml(static::convertToOutputCharset($variable, $inputCharset))) . '</span>' .
			'<span class="quote close">&quot;</span>';
	}

	/**
	 * @param string $string
	 * @return string
	 */
	static protected function highlightSpaces($string) {
		$cr = '<span class="char cr" title="' . static::translateAndEscapeHtml('Carriage return ("\r")') . '"></span>';
		$lf = '<span class="char lf" title="' . static::translateAndEscapeHtml('Line feed ("\n")') . '"></span>';

		$string = strtr($string, array(
			" " => '<span class="char space" title="' . static::translateAndEscapeHtml('Whitespace (" ")') . '"> </span>',
			"\t" => '<span class="char tab" title="' . static::translateAndEscapeHtml('Tab ("\t")') . '">' . "\t" . '</span>',
			"\r\n" => $cr . $lf . "\r\n",
			"\r" => $cr . "\r",
			"\n" => $lf . "\n",
		));

		return $string;
	}
}