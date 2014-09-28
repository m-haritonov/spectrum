<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\code\variables;

class boolVar extends \spectrum\core\plugins\reports\drivers\text\components\component {
	/**
	 * @param bool $variable
	 * @return string
	 */
	static public function getContent($variable) {
		return static::translate('bool') . ' ' . ($variable ? 'true' : 'false');
	}
}