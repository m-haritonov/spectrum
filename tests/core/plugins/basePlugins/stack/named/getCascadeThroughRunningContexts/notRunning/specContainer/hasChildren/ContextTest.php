<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\stack\named\getCascadeThroughRunningContexts\notRunning\specContainer\hasChildren;
use spectrum\core\plugins\basePlugins\stack\Named;

require_once __DIR__ . '/../../../../../../../../../init.php';

class ContextTest extends Test
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerContext';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecContainerContextMock';

	protected function executeContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return \spectrum\core\testEnv\ContextsExecutor::notRunningSpecContainerHasChildrenContext($callback, $spec);
	}
}