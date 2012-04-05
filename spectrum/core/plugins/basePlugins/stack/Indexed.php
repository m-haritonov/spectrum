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