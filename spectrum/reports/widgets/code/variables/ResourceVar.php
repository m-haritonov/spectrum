<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets\code\variables;

class ResourceVar extends Variable
{
	protected $type = 'resource';

	protected function getHtmlForValue($variable)
	{
		return ' <span class="value">' . htmlspecialchars(print_r($variable, true)) . '</span>';
	}
}