<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;

/**
 *
 * Support params variants:
 * it($name)
 * it($name, $settings)
 * it($name, $testCallback)
 * it($name, $testCallback, $settings)
 * it($name, $argumentsProvider, $testCallback)
 * it($name, $argumentsProvider, $testCallback, $settings)
 *
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state or if data provider is bad
 * @param  string|null $name
 * @param  array|null $argumentsProvider
 * @param  callback|null $testCallback
 * @return \spectrum\core\SpecItemIt
 */
function it($name, $argumentsProvider = null, $testCallback = null, $settings = array())
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isDeclaringState())
		throw new \spectrum\constructionCommands\Exception('Construction command "it" should be call only at declaring state');

	$arg1 = $name;
	$arg2 = $argumentsProvider;
	$arg3 = $testCallback;

	$isArg1Closure = (is_object($arg1) && is_callable($arg1));
	$isArg2Closure = (is_object($arg2) && is_callable($arg2));
	$isArg3Closure = (is_object($arg3) && is_callable($arg3));


	if (!$isArg1Closure && !$isArg2Closure && !$isArg3Closure) // it($name [, $settings])
	{
		$argumentsProvider = null;
		$testCallback = null;
		if ($arg2 !== null)
			$settings = $arg2;
	}
	else if (!$isArg1Closure && $isArg2Closure) // it($name, $testCallback [, $settings])
	{
		$argumentsProvider = null;
		$testCallback = $arg2;
		if ($arg3 !== null)
			$settings = $arg3;
	}

	if ($argumentsProvider !== null)
	{
		if (!is_array($argumentsProvider))
			throw new \spectrum\constructionCommands\Exception('Construction command "it" should be accept array as $argumentsProvider (now passed "' . gettype($argumentsProvider) . '")');

		$argumentsProviderClass = \spectrum\core\Config::getSpecContainerArgumentsProviderClass();
		$spec = new $argumentsProviderClass();
		$spec->setName($name);
		$spec->createSpecItemForEachArgumentsRow($testCallback, $argumentsProvider);
	}
	else
	{
		$itClass = \spectrum\core\Config::getSpecItemItClass();
		$spec = new $itClass();
		$spec->setName($name);
		$spec->setTestCallback($testCallback);
	}

	$managerClass::setSettings($spec, $settings);
	$managerClass::getCurrentContainer()->addSpec($spec);
	return $spec;
}