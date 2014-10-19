<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\details;

use spectrum\core\details\UserFailInterface;

class userFail extends \spectrum\core\plugins\reports\drivers\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-details-userFail { padding: 7px; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @return string
	 */
	static public function getContent(UserFailInterface $details) {
		return
			'<div class="app-details-userFail">' .
				'<p>' . static::escapeHtml($details->getMessage()) . '</p>' .
			'</div>';
	}
}