<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private\reports\html\components\code\variables;

class boolVar extends \spectrum\_private\reports\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-code-variables-bool { font-size: 12px; }
			.app-code-variables-bool .type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.app-code-variables-bool .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-bool .value { overflow: visible; max-width: none; white-space: normal; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @param bool $variable
	 * @return string
	 */
	static public function getContent($variable) {
		return
			'<span class="app-code-variables-bool">' .
				'<span class="type">' . static::translateAndEscapeHtml('bool') . '</span> ' .
				'<span class="value">' . ($variable ? 'true' : 'false') . '</span>' .
			'</span>';
	}
}