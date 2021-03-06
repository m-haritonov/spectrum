<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\text\components\details;

class unknown extends \spectrum\core\_private\reports\text\components\component {
	/**
	 * @param mixed $details
	 * @return string
	 */
	static public function getContent($details) {
		return static::callComponentMethod('code\variable', 'getContent', array($details));
	}
}