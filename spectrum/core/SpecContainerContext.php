<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

class SpecContainerContext extends SpecContainer implements SpecContainerContextInterface
{
	public function getSpecsToRun()
	{
		$childContexts = $this->selector->getChildContexts();
		
		if (count($childContexts))
			return $childContexts;
		else
			return $this->selector->getNotContextSpecsUpToNearestNotContextAncestor();
	}

	protected function isSibling(SpecInterface $spec)
	{
		return ($spec instanceof SpecContainerContextInterface);
	}
}