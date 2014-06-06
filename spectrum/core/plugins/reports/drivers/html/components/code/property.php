<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code;

class property extends \spectrum\core\plugins\reports\drivers\html\components\component
{
	/**
	 * @param string $propertyName String in "us-ascii" charset
	 * @return string
	 */
	static public function getHtml($propertyName, $inputCharset = null)
	{
		return '<span class="c-code-property">' . static::escapeHtml(static::convertToOutputCharset($propertyName, $inputCharset)) . '</span>';
	}
}