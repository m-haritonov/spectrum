<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets\code\variables;

class NullVar extends Variable
{
	protected $type = 'null';

	protected function getHtmlForType($variable)
	{
		return null;
	}

	protected function getHtmlForValue($variable)
	{
		return '<span class="value">null</span>';
	}
}