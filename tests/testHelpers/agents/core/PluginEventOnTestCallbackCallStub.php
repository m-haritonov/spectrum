<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\testHelpers\agents\core;

class PluginEventOnTestCallbackCallStub extends \spectrum\core\plugins\Plugin implements \spectrum\core\plugins\events\OnTestCallbackCallBeforeInterface, \spectrum\core\plugins\events\OnTestCallbackCallAfterInterface
{
	public function onTestCallbackCallBefore(\spectrum\core\DataInterface $world)
	{
		\spectrum\tests\Test::$temp['triggeredEvents']['onTestCallbackCall'][] = array(
			'name' => __FUNCTION__,
			'arguments' => func_get_args(),
			'owner' => $this->getOwnerSpec(),
			'isRunning' => $this->getOwnerSpec()->isRunning(),
			'resultBuffer' => $this->getOwnerSpec()->getResultBuffer(),
			'worldFooValue' => @$world->foo,
		);
	}

	public function onTestCallbackCallAfter(\spectrum\core\DataInterface $world)
	{
		\spectrum\tests\Test::$temp['triggeredEvents']['onTestCallbackCall'][] = array(
			'name' => __FUNCTION__,
			'arguments' => func_get_args(),
			'owner' => $this->getOwnerSpec(),
			'isRunning' => $this->getOwnerSpec()->isRunning(),
			'resultBuffer' => $this->getOwnerSpec()->getResultBuffer(),
			'worldFooValue' => @$world->foo,
		);
	}
}