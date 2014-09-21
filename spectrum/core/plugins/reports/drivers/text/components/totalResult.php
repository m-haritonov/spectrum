<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components;

use spectrum\core\SpecInterface;

class totalResult extends component {
	static public function getContent(SpecInterface $spec) {
		return static::translate(static::getResultName($spec->getResultBuffer()->getTotalResult()));
	}

	static protected function getResultName($result) {
		if ($result === false) {
			return 'fail';
		} else if ($result === true) {
			return 'success';
		} else {
			return 'empty';
		}
	}
}