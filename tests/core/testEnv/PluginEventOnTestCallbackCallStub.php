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

namespace net\mkharitonov\spectrum\core\testEnv;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class PluginEventOnTestCallbackCallStub extends \net\mkharitonov\spectrum\core\plugins\Plugin implements \net\mkharitonov\spectrum\core\plugins\events\OnTestCallbackCallInterface
{
	public function onTestCallbackCallBefore(\net\mkharitonov\spectrum\core\WorldInterface $world)
	{
		\net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onTestCallbackCall'][] = array(
			'name' => __FUNCTION__,
			'arguments' => func_get_args(),
			'owner' => $this->getOwnerSpec(),
			'isRunning' => $this->getOwnerSpec()->isRunning(),
			'runResultsBuffer' => $this->getOwnerSpec()->getRunResultsBuffer(),
			'worldFooValue' => @$world->foo,
		);
	}

	public function onTestCallbackCallAfter(\net\mkharitonov\spectrum\core\WorldInterface $world)
	{
		\net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onTestCallbackCall'][] = array(
			'name' => __FUNCTION__,
			'arguments' => func_get_args(),
			'owner' => $this->getOwnerSpec(),
			'isRunning' => $this->getOwnerSpec()->isRunning(),
			'runResultsBuffer' => $this->getOwnerSpec()->getRunResultsBuffer(),
			'worldFooValue' => @$world->foo,
		);
	}
}