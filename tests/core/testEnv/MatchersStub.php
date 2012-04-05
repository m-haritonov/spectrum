<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv;

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