<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code\variables;

use spectrum\config;

class stringVar extends \spectrum\core\plugins\reports\drivers\html\components\component
{
	static public function getStyles()
	{
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-code-variables-string { font-size: 12px; }
			.c-code-variables-string .type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.c-code-variables-string .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.c-code-variables-string .quote { color: rgba(0, 0, 0, 0.6); }
		
			.c-resultBuffer>.results>.result.expand .c-code-variables-string .value { overflow: visible; max-width: none; white-space: pre; }
			.c-resultBuffer>.results>.result.expand .c-code-variables-string .value .char { display: inline-block; overflow: hidden; position: relative; height: 12px; }

			.c-resultBuffer>.results>.result.expand .c-code-variables-string .value .char.space { width: 8px; height: 10px; }
			.c-resultBuffer>.results>.result.expand .c-code-variables-string .value .char.space:before { content: "\\0020"; position: absolute; bottom: 1px; left: 49%; width: 2px; height: 2px; background: #bbb; }

			.c-resultBuffer>.results>.result.expand .c-code-variables-string .value .char.tab { width: 15px; }
			.c-resultBuffer>.results>.result.expand .c-code-variables-string .value .char.tab:before { content: "\\21E5"; position: absolute; right: 0; left: 0; text-align: center; color: #aaa; }

			.c-resultBuffer>.results>.result.expand .c-code-variables-string .value .char.cr { width: 14px; }
			.c-resultBuffer>.results>.result.expand .c-code-variables-string .value .char.cr:before { content: "\\21A9"; position: absolute; bottom: -1px; right: 0; left: 0; text-align: center; color: #aaa; }

			.c-resultBuffer>.results>.result.expand .c-code-variables-string .value .char.lf { width: 10px; height: 11px; }
			.c-resultBuffer>.results>.result.expand .c-code-variables-string .value .char.lf:before { content: "\\2193"; position: absolute; bottom: 3px; right: 0; left: 0; text-align: center; color: #aaa; }
			.c-resultBuffer>.results>.result.expand .c-code-variables-string .value .char.lf:after { content: "\\0020"; position: absolute; bottom: 2px; right: 2px; left: 2px; border-bottom: 1px solid #bbb; }
		/*]]>*/</style>', 2);
	}
	
	static public function getHtml($variable, $inputCharset = null)
	{
		return
			'<span class="c-code-variables-string">' .
				static::getHtmlForType($variable, $inputCharset) .
				static::getHtmlForValue($variable, $inputCharset) .
			'</span>';
	}

	static protected function getHtmlForType($variable, $inputCharset)
	{
		if ($inputCharset === null)
			$inputCharset = config::getInputCharset();
		
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

	static protected function getHtmlForValue($variable, $inputCharset)
	{
		return
			'<span class="quote open">&quot;</span>' .
				'<span class="value">' . static::highlightSpaces(static::escapeHtml(static::convertToOutputCharset($variable, $inputCharset))) . '</span>' .
			'<span class="quote close">&quot;</span>';
	}

	static protected function highlightSpaces($string)
	{
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