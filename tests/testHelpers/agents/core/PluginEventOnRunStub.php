<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\testHelpers\agents\core;

class PluginEventOnRunStub extends \spectrum\core\plugins\Plugin implements \spectrum\core\plugins\events\OnRunBeforeInterface, \spectrum\core\plugins\events\OnRunAfterInterface
{
	static private $onBeforeCallback;

	static public function setOnBeforeCallback($callback)
	{
		static::$onBeforeCallback = $callback;
	}

	public function onRunBefore()
	{
		\spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][] = array(
			'name' => __FUNCTION__,
			'arguments' => func_get_args(),
			'isRunning' => $this->getOwnerSpec()->isRunning(),
			'owner' => $this->getOwnerSpec(),
		);

		if (static::$onBeforeCallback)
			call_user_func(static::$onBeforeCallback, $this);
	}

	public function onRunAfter($result)
	{
		\spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][] = array(
			'name' => __FUNCTION__,
			'arguments' => func_get_args(),
			'isRunning' => $this->getOwnerSpec()->isRunning(),
			'owner' => $this->getOwnerSpec(),
		);
	}
}