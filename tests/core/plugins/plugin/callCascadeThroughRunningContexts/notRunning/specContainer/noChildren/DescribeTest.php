<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\notRunning\specContainer\noChildren;
require_once dirname(__FILE__) . '/../../../../../../../init.php';

class DescribeTest extends \spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\notRunning\Test
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerDescribe';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecContainerDescribeMock';

	protected function executeContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return \spectrum\core\testEnv\ContextsExecutor::notRunningSpecContainerNoChildrenDescribe($callback, $spec);
	}
}