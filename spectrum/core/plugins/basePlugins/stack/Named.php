<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace spectrum\core\plugins\basePlugins\stack;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
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