<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private\reports\html\components\details;

class unknown extends \spectrum\_private\reports\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-details-unknown { padding: 7px; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @param mixed $details
	 * @return string
	 */
	static public function getContent($details) {
		return
			'<div class="app-details-unknown">' .
				static::callComponentMethod('code\variable', 'getContent', array($details)) .
			'</div>';
	}
}