<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\html\components\code\variables;

class resourceVar extends \spectrum\core\_private\reports\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-code-variables-resource { font-size: 12px; }
			.app-code-variables-resource .type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.app-code-variables-resource .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.app-results>.results>.result.expanded .app-code-variables-resource .value { overflow: visible; max-width: none; white-space: normal; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @param resource $variable
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($variable, $inputCharset = null) {
		return
			'<span class="app-code-variables-resource">' .
				'<span class="type">' . static::translateAndEscapeHtml('resource') . '</span> ' .
				'<span class="value">' . static::escapeHtml(static::convertToOutputCharset(print_r($variable, true), $inputCharset)) . '</span>' .
			'</span>';
	}
}