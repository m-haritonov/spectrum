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