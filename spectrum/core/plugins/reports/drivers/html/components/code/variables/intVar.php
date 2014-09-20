<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code\variables;

class intVar extends \spectrum\core\plugins\reports\drivers\html\components\component {
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-code-variables-int { font-size: 12px; }
			.c-code-variables-int .type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.c-code-variables-int .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.c-resultBuffer>.results>.result.expanded .c-code-variables-int .value { overflow: visible; max-width: none; white-space: normal; }
		/*]]>*/</style>', 2);
	}

	static public function getHtml($variable) {
		return
			'<span class="c-code-variables-int">' .
				'<span class="type">' . static::translateAndEscapeHtml('int') . '</span> ' .
				'<span class="value">' . static::escapeHtml($variable) . '</span>' .
			'</span>';
	}
}