<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv;

class PluginEventOnRunStub extends \spectrum\core\plugins\Plugin implements \spectrum\core\plugins\events\OnRunInterface
{
	static private $onBeforeCallback;

	static public function setOnBeforeCallback($callback)
	{
		static::$onBeforeCallback = $callback;
	}

	public function onRunBefore()
	{
		\spectrum\Test::$tmp['triggeredEvents']['onRun'][] = array(
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
		\spectrum\Test::$tmp['triggeredEvents']['onRun'][] = array(
			'name' => __FUNCTION__,
			'arguments' => func_get_args(),
			'isRunning' => $this->getOwnerSpec()->isRunning(),
			'owner' => $this->getOwnerSpec(),
		);
	}
}