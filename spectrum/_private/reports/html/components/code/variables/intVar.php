<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private\reports\html\components\code\variables;

class intVar extends \spectrum\_private\reports\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-code-variables-int { font-size: 12px; }
			.app-code-variables-int .type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.app-code-variables-int .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-int .value { overflow: visible; max-width: none; white-space: normal; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @param int $variable
	 * @return string
	 */
	static public function getContent($variable) {
		return
			'<span class="app-code-variables-int">' .
				'<span class="type">' . static::translateAndEscapeHtml('int') . '</span> ' .
				'<span class="value">' . static::escapeHtml($variable) . '</span>' .
			'</span>';
	}
}