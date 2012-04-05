<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv;

/**
 * Contains methods for not abstract classes.
 */
class ContextsExecutor
{
	static public function notRunningSpecContainerHasChildrenContext($callback, \spectrum\core\SpecInterface $spec)
	{
		$callback();
	}

	static public function notRunningSpecContainerHasChildrenDescribe($callback, \spectrum\core\SpecInterface $spec)
	{
		$callback();
	}

	static public function notRunningSpecContainerNoChildrenContext($callback, \spectrum\core\SpecInterface $spec)
	{
		$callback();
	}

	static public function notRunningSpecContainerNoChildrenDescribe($callback, \spectrum\core\SpecInterface $spec)
	{
		$callback();
	}


	static public function notRunningSpecItemIt($callback, \spectrum\core\SpecInterface $spec)
	{
		$callback();
	}

/**/

	static public function runningSpecContainerHasChildrenContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return static::runningSpecContainerHasChildren($callback, $spec);
	}

	static public function runningSpecContainerHasChildrenDescribe($callback, \spectrum\core\SpecInterface $spec)
	{
		return static::runningSpecContainerHasChildren($callback, $spec);
	}


	static public function runningSpecContainerNoChildrenContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return static::runningSpecContainerNoChildren($callback, $spec);
	}

	static public function runningSpecContainerNoChildrenDescribe($callback, \spectrum\core\SpecInterface $spec)
	{
		return static::runningSpecContainerNoChildren($callback, $spec);
	}


	static public function runningSpecItemIt($callback, \spectrum\core\SpecInterface $spec)
	{
		$spec->setTestCallback($callback);
		$spec->run();
	}

/**/

	static private function runningSpecContainerHasChildren($callback, \spectrum\core\SpecInterface $spec)
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->setTestCallback($callback);

		$spec->addSpec($it);
		$spec->run();
	}

	static private function runningSpecContainerNoChildren($callback, \spectrum\core\SpecInterface $spec)
	{
		$pluginName = str_replace('\\', '_', __CLASS__);

		\spectrum\core\plugins\Manager::registerPlugin($pluginName, '\spectrum\core\testEnv\PluginEventOnRunStub');

		\spectrum\core\testEnv\PluginEventOnRunStub::setOnBeforeCallback(function($plugin) use($callback, $spec)
		{
			if ($plugin->getOwnerSpec() === $spec)
				$callback();
		});

		$spec->run();

		\spectrum\core\testEnv\PluginEventOnRunStub::setOnBeforeCallback(null);

		\spectrum\core\plugins\Manager::unregisterPlugin($pluginName);
	}
}