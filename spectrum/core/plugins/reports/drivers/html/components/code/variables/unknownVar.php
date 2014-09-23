<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code\variables;

class unknownVar extends \spectrum\core\plugins\reports\drivers\html\components\component {
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-code-variables-unknown { font-size: 12px; }
			.app-code-variables-unknown .type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); font-style: italic; }
			.app-code-variables-unknown .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-unknown .value { overflow: visible; max-width: none; white-space: normal; }
		/*]]>*/</style>', 2);
	}

	static public function getContent($variable, $inputCharset = null) {
		return
			'<span class="app-code-variables-unknown">' .
				'<span class="type">' . static::translateAndEscapeHtml('unknown') . '</span> ' .
				'<span class="value">' . static::escapeHtml(static::convertToOutputCharset(print_r($variable, true), $inputCharset)) . '</span>' .
			'</span>';
	}
}