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