<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\stack;

class Indexed extends Stack
{
	public function add($value)
	{
		$this->items[] = $value;
		return $value;
	}

	public function remove($index)
	{
		return parent::remove($index);
	}

	public function isExists($index)
	{
		return parent::isExists($index);
	}

	public function get($index)
	{
		return parent::get($index);
	}
}