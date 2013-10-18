<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\code;

class Property extends \spectrum\core\plugins\basePlugins\reports\drivers\html\components\Component
{
	public function getHtml($propertyName)
	{
		return '<span class="c-code-property">' . htmlspecialchars($propertyName) . '</span>';
	}
}