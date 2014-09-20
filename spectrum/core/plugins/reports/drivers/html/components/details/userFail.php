<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\details;

use spectrum\core\details\UserFailInterface;

class userFail extends \spectrum\core\plugins\reports\drivers\html\components\component {
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-details-userFail { padding: 7px; }
		/*]]>*/</style>', 2);
	}

	static public function getHtml(UserFailInterface $details) {
		return
			'<div class="c-details-userFail">' . static::getHtmlEscapedOutputNewline() .
				'<p>' . static::escapeHtml($details->getMessage()) . '</p>' .
			'</div>';
	}
}