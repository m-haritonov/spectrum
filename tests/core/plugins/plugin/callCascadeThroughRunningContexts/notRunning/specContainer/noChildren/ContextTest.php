<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\notRunning\specContainer\noChildren;
require_once dirname(__FILE__) . '/../../../../../../../init.php';

class ContextTest extends \spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\notRunning\Test
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerContext';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecContainerContextMock';

	protected function executeContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return \spectrum\core\testEnv\ContextsExecutor::notRunningSpecContainerNoChildrenContext($callback, $spec);
	}
}