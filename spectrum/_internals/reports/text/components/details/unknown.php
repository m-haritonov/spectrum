<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals\reports\text\components\details;

class unknown extends \spectrum\_internals\reports\text\components\component {
	/**
	 * @param mixed $details
	 * @return string
	 */
	static public function getContent($details) {
		return static::callComponentMethod('code\variable', 'getContent', array($details));
	}
}