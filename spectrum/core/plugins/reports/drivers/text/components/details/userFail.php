<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\details;

use spectrum\core\details\UserFailInterface;

class userFail extends \spectrum\core\plugins\reports\drivers\text\components\component {
	static public function getContent(UserFailInterface $details) {
		return $details->getMessage();
	}
}