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

namespace spectrum\core\testEnv;

class PluginEventOnTestCallbackCallStub extends \spectrum\core\plugins\Plugin implements \spectrum\core\plugins\events\OnTestCallbackCallInterface
{
	public function onTestCallbackCallBefore(\spectrum\core\WorldInterface $world)
	{
		\spectrum\Test::$tmp['triggeredEvents']['onTestCallbackCall'][] = array(
			'name' => __FUNCTION__,
			'arguments' => func_get_args(),
			'owner' => $this->getOwnerSpec(),
			'isRunning' => $this->getOwnerSpec()->isRunning(),
			'runResultsBuffer' => $this->getOwnerSpec()->getRunResultsBuffer(),
			'worldFooValue' => @$world->foo,
		);
	}

	public function onTestCallbackCallAfter(\spectrum\core\WorldInterface $world)
	{
		\spectrum\Test::$tmp['triggeredEvents']['onTestCallbackCall'][] = array(
			'name' => __FUNCTION__,
			'arguments' => func_get_args(),
			'owner' => $this->getOwnerSpec(),
			'isRunning' => $this->getOwnerSpec()->isRunning(),
			'runResultsBuffer' => $this->getOwnerSpec()->getRunResultsBuffer(),
			'worldFooValue' => @$world->foo,
		);
	}
}