<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\running;
require_once dirname(__FILE__) . '/../../../../../init.php';

class SpecItemItTest extends Test
{
	protected $currentSpecClass = '\spectrum\core\SpecItemIt';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecItemItMock';

	protected function executeContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return \spectrum\core\testEnv\ContextsExecutor::runningSpecItemIt($callback, $spec);
	}
}