<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\notRunning\specContainer\hasChildren;
require_once __DIR__ . '/../../../../../../../init.php';

class DescribeTest extends \spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\notRunning\Test
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerDescribe';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecContainerDescribeMock';

	protected function executeContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return \spectrum\core\testEnv\ContextsExecutor::notRunningSpecContainerHasChildrenDescribe($callback, $spec);
	}
}