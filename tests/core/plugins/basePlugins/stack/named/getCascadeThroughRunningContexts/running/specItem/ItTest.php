<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\stack\named\getCascadeThroughRunningContexts\running\specItem;
use spectrum\core\plugins\basePlugins\stack\Named;

require_once dirname(__FILE__) . '/../../../../../../../../init.php';

class ItTest extends Test
{
	protected $currentSpecClass = '\spectrum\core\SpecItemIt';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecItemItMock';

	protected function executeContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return \spectrum\core\testEnv\ContextsExecutor::runningSpecItemIt($callback, $spec);
	}
}