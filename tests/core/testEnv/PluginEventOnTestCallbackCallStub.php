<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
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