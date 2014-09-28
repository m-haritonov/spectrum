<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code\variables;

class recursionVar extends \spectrum\core\plugins\reports\drivers\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-code-variables-recursion { font-size: 12px; }
			.app-code-variables-recursion .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-recursion .value { overflow: visible; max-width: none; white-space: normal; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @return string
	 */
	static public function getContent() {
		return
			'<span class="app-code-variables-recursion">' .
				'<span class="value">...' . static::translateAndEscapeHtml('recursion') . '...</span>' .
			'</span>';
	}
}