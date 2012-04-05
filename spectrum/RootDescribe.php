<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum;

class RootDescribe
{
	/**
	 * @var \spectrum\core\SpecContainerDescribe
	 */
	protected static $onceInstance;

	static public function getOnceInstance()
	{
		if (!static::$onceInstance)
		{
			static::$onceInstance = static::createSpecContainer();
			static::configureSpec(static::$onceInstance);
			static::addMatchersToSpec(static::$onceInstance);
		}

		return static::$onceInstance;
	}

	static protected function createSpecContainer()
	{
		$describeClass = \spectrum\core\Config::getSpecContainerDescribeClass();
		return new $describeClass();
	}

	static protected function configureSpec(\spectrum\core\SpecContainerInterface $spec)
	{
		$spec->errorHandling->setCatchExceptions(true);
		$spec->errorHandling->setCatchPhpErrors(true);
		$spec->errorHandling->setBreakOnFirstPhpError(false);
		$spec->errorHandling->setBreakOnFirstMatcherFail(false);
	}

	static protected function addMatchersToSpec(\spectrum\core\SpecContainerInterface $spec)
	{
		$managerClass = \spectrum\matchers\Config::getManagerClass();
		$managerClass::addAllMatchersToSpec($spec);
	}

	static public function run()
	{
		return static::getOnceInstance()->run();
	}
}