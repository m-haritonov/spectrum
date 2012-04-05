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

namespace spectrum\core\plugins\basePlugins\stack\named\getCascadeThroughRunningContexts\running\specContainer\hasChildren;
use spectrum\core\plugins\basePlugins\stack\Named;

require_once dirname(__FILE__) . '/../../../../../../../../../init.php';

class DescribeTest extends Test
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerDescribe';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecContainerDescribeMock';

	protected function executeContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return \spectrum\core\testEnv\ContextsExecutor::runningSpecContainerHasChildrenDescribe($callback, $spec);
	}
}