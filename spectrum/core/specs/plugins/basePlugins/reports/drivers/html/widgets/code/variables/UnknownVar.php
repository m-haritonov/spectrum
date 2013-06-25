<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets\code\variables;

class UnknownVar extends Variable
{
	protected $type = 'unknown';

	protected function getHtmlForValue($variable)
	{
		return ' <span class="value">' . htmlspecialchars(print_r($variable, true)) . '</span>';
	}
}