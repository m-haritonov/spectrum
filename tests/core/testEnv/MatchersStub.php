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

namespace spectrum\core\testEnv;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class MatchersStub extends \spectrum\core\plugins\basePlugins\Matchers
{
	public function getFromSelfOrAncestor($key)
	{
		return $this->get($key);
	}

	public function getAllPrependAncestors()
	{
		return $this->getAll();
	}

	public function getAllAppendAncestors()
	{
		return $this->getAll();
	}

	public function callMatcher($name, array $args = array())
	{
		if ($name == 'true')
			return true;
		else
			return false;
	}
}