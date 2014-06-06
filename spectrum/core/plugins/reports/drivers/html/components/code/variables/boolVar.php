<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code\variables;

class boolVar extends \spectrum\core\plugins\reports\drivers\html\components\component
{
	static public function getStyles()
	{
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-code-variables-bool { font-size: 12px; }
			.c-code-variables-bool .type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.c-code-variables-bool .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.c-resultBuffer>.results>.result.expanded .c-code-variables-bool .value { overflow: visible; max-width: none; white-space: normal; }
		/*]]>*/</style>', 2);
	}
	
	static public function getHtml($variable)
	{
		return
			'<span class="c-code-variables-bool">' .
				'<span class="type">' . static::translateAndEscapeHtml('bool') . '</span> ' .
				'<span class="value">' . ($variable ? 'true' : 'false') . '</span>' .
			'</span>';
	}
}