<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\stack\named\getCascadeThroughRunningContexts\running\specContainer\noChildren;
use spectrum\core\plugins\basePlugins\stack\Named;

require_once dirname(__FILE__) . '/../../../../../../../../../init.php';

class DescribeTest extends Test
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerDescribe';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecContainerDescribeMock';

	protected function executeContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return \spectrum\core\testEnv\ContextsExecutor::runningSpecContainerNoChildrenDescribe($callback, $spec);
	}
}