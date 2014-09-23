<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\details;

class unknown extends \spectrum\core\plugins\reports\drivers\html\components\component {
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-details-unknown { padding: 7px; }
		/*]]>*/</style>', 2);
	}

	static public function getContent($details) {
		return
			'<div class="app-details-unknown">' .
				static::callComponentMethod('code\variable', 'getContent', array($details)) .
			'</div>';
	}
}