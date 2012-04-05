<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\stack;

class Named extends Stack
{
	public function add($name, $value)
	{
		$this->items[$name] = $value;
		return $value;
	}

	public function remove($name)
	{
		return parent::remove($name);
	}

	public function isExists($name)
	{
		return parent::isExists($name);
	}

	public function get($name)
	{
		return parent::get($name);
	}
}