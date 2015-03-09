<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\html\components\code\variables;

class floatVar extends \spectrum\core\_private\reports\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-code-variables-float { font-size: 12px; }
			.app-code-variables-float .type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.app-code-variables-float .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.app-results>.results>.result.expanded .app-code-variables-float .value { overflow: visible; max-width: none; white-space: normal; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @param float $variable
	 * @return string
	 */
	static public function getContent($variable) {
		return
			'<span class="app-code-variables-float">' .
				'<span class="type">' . static::translateAndEscapeHtml('float') . '</span> ' .
				'<span class="value">' . static::escapeHtml($variable) . '</span>' .
			'</span>';
	}
}