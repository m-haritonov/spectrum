<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets\code;

class Property extends \spectrum\reports\widgets\Widget
{
	public function getHtml($propertyName)
	{
		return '<span class="g-code-property">' . htmlspecialchars($propertyName) . '</span>';
	}
}