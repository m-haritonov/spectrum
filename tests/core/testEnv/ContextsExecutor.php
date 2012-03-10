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
 * Contains methods for not abstract classes.
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ContextsExecutor
{
	static public function notRunningSpecContainerHasChildrenContext($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		$callback();
	}

	static public function notRunningSpecContainerHasChildrenDescribe($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		$callback();
	}

	static public function notRunningSpecContainerNoChildrenContext($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		$callback();
	}

	static public function notRunningSpecContainerNoChildrenDescribe($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		$callback();
	}


	static public function notRunningSpecItemIt($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		$callback();
	}

/**/

	static public function runningSpecContainerHasChildrenContext($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		return static::runningSpecContainerHasChildren($callback, $spec);
	}

	static public function runningSpecContainerHasChildrenDescribe($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		return static::runningSpecContainerHasChildren($callback, $spec);
	}


	static public function runningSpecContainerNoChildrenContext($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		return static::runningSpecContainerNoChildren($callback, $spec);
	}

	static public function runningSpecContainerNoChildrenDescribe($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		return static::runningSpecContainerNoChildren($callback, $spec);
	}


	static public function runningSpecItemIt($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		$spec->setTestCallback($callback);
		$spec->run();
	}

/**/

	static private function runningSpecContainerHasChildren($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		$it = new \net\mkharitonov\spectrum\core\SpecItemIt();
		$it->setTestCallback($callback);

		$spec->addSpec($it);
		$spec->run();
	}

	static private function runningSpecContainerNoChildren($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		$pluginName = str_replace('\\', '_', __CLASS__);

		\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin($pluginName, '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub::setOnBeforeCallback(function($plugin) use($callback, $spec)
		{
			if ($plugin->getOwnerSpec() === $spec)
				$callback();
		});

		$spec->run();

		\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub::setOnBeforeCallback(null);

		\net\mkharitonov\spectrum\core\plugins\Manager::unregisterPlugin($pluginName);
	}
}