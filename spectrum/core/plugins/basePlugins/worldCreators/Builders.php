<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\worldCreators;

class Builders extends WorldCreators
{
	public function applyToWorld($world)
	{
		foreach ($this->getAllPrependAncestorsWithRunningContexts() as $creator)
		{
			call_user_func($creator['callback'], $world);
		}

		return $world;
	}
}