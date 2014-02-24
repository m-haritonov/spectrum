<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables;

class floatVar extends \spectrum\core\plugins\basePlugins\reports\drivers\html\components\component
{
	static public function getStyles()
	{
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-code-variables-float { font-size: 12px; }
			.c-code-variables-float .type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.c-code-variables-float .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.c-resultBuffer>.results>.result.expand .c-code-variables-float .value { overflow: visible; max-width: none; white-space: normal; }
		/*]]>*/</style>', 2);
	}
	
	static public function getHtml($variable)
	{
		return
			'<span class="c-code-variables-float">' .
				'<span class="type">' . static::translateAndEscapeHtml('float') . '</span> ' .
				'<span class="value">' . static::escapeHtml($variable) . '</span>' .
			'</span>';
	}
}