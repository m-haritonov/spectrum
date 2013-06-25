<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets\code;

class Property extends \spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets\Widget
{
	public function getHtml($propertyName)
	{
		return '<span class="g-code-property">' . htmlspecialchars($propertyName) . '</span>';
	}
}