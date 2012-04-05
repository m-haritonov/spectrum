<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;

/**
 * Function with base functional for construction commands describe() and context()
 *
 * Support params variants:
 * container($specClass, $name)
 * container($specClass, $name, $settings)
 *
 * container($specClass, $callback)
 * container($specClass, $callback, $settings)
 *
 * container($specClass, $name, $callback)
 * container($specClass, $name, $callback, $settings)
 *
 * @param  string $specClass
 * @param  string|callback $nameOrCallback
 * @param  callback|null $callback
 * @return \spectrum\core\SpecContainer
 */
function container($specClass, $name = null, $callback = null, $settings = array())
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();

	$arg1 = $name;
	$arg2 = $callback;

	$isArg1Closure = (is_object($arg1) && is_callable($arg1));
	$isArg2Closure = (is_object($arg2) && is_callable($arg2));

	if (!$isArg1Closure && !$isArg2Closure) // container($specClass, $name [, $settings])
	{
		$name = $arg1;
		$callback = null;
		if ($arg2 !== null)
			$settings = $arg2;
	}
	else if ($isArg1Closure) // container($specClass, $callback [, $settings])
	{
		$name = null;
		$callback = $arg1;
		if ($arg2 !== null)
			$settings = $arg2;
	}
	else if ($isArg2Closure) // container($specClass, $name, $callback [, $settings])
	{
		$name = $arg1;
		$callback = $arg2;
	}

	$spec = new $specClass();
	$spec->setName($name);
	$managerClass::setSettings($spec, $settings);

	$managerClass::getCurrentContainer()->addSpec($spec);

	if ($callback)
	{
		$declaringSpecContainerBackup = $managerClass::getDeclaringContainer();
		$managerClass::setDeclaringContainer($spec);
		call_user_func($callback);
		$managerClass::setDeclaringContainer($declaringSpecContainerBackup);
	}

	return $spec;
}