<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv;

class WorldCreatorsDestroyersStub extends \spectrum\core\plugins\basePlugins\worldCreators\Destroyers
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
}