<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\details;

class unknown extends \spectrum\core\plugins\reports\drivers\text\components\component {
	static public function getContent($details) {
		return static::callComponentMethod('code\variable', 'getContent', array($details));
	}
}