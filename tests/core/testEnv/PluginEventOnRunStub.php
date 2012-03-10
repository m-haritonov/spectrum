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
class PluginEventOnRunStub extends \net\mkharitonov\spectrum\core\plugins\Plugin implements \net\mkharitonov\spectrum\core\plugins\events\OnRunInterface
{
	static private $onBeforeCallback;

	static public function setOnBeforeCallback($callback)
	{
		static::$onBeforeCallback = $callback;
	}

	public function onRunBefore()
	{
		\net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][] = array(
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
		\net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][] = array(
			'name' => __FUNCTION__,
			'arguments' => func_get_args(),
			'isRunning' => $this->getOwnerSpec()->isRunning(),
			'owner' => $this->getOwnerSpec(),
		);
	}
}