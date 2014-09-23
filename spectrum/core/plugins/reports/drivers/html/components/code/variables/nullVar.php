<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code\variables;

class nullVar extends \spectrum\core\plugins\reports\drivers\html\components\component {
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-code-variables-null { font-size: 12px; }
			.c-code-variables-null .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.c-resultBuffer>.results>.result.expanded .c-code-variables-null .value { overflow: visible; max-width: none; white-space: normal; }
		/*]]>*/</style>', 2);
	}

	static public function getContent() {
		return
			'<span class="c-code-variables-null">' .
				'<span class="value">null</span>' .
			'</span>';
	}
}