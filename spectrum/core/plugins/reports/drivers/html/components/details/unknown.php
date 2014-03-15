<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\details;

class unknown extends \spectrum\core\plugins\reports\drivers\html\components\component
{
	static public function getStyles()
	{
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-details-unknown { padding: 7px; }
		/*]]>*/</style>', 2);
	}

	static public function getHtml($details)
	{
		return
			'<div class="c-details-unknown">' .
				static::callComponentMethod('code\variable', 'getHtml', array($details)) .
			'</div>';
	}
}