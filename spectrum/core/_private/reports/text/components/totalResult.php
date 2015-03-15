<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\text\components;

use spectrum\core\models\SpecInterface;

class totalResult extends component {
	/**
	 * @return string
	 */
	static public function getContent(SpecInterface $spec) {
		return static::translate(static::getResultName($spec->getResults()->getTotal()));
	}

	/**
	 * @param null|bool $result
	 * @return string
	 */
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