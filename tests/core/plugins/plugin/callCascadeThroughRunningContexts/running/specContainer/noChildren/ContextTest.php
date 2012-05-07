<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\running\specContainer\noChildren;
require_once __DIR__ . '/../../../../../../../init.php';

class ContextTest extends \spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\running\Test
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerContext';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecContainerContextMock';

	protected function executeContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return \spectrum\core\testEnv\ContextsExecutor::runningSpecContainerNoChildrenContext($callback, $spec);
	}
}