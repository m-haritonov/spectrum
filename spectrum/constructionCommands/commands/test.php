<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands;
use spectrum\config;

/**
 *
 * Support params variants:
 * test(scalar $name)
 * test(scalar $name, array $settings)
 * test(scalar $name, \Closure $testFunction)
 * test(scalar $name, \Closure $testFunction, array $settings)
 * test(scalar $name, \Closure $multiplier, \Closure $testFunction)
 * test(scalar $name, array $multiplier, \Closure $testFunction)
 * test(scalar $name, \Closure $multiplier, \Closure $testFunction, array $settings)
 * test(scalar $name, array $multiplier, \Closure $testFunction, array $settings)
 *
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state or if data provider is bad
 * @param  string|null $name
 * @param  array|\Closure|null $multiplier
 * @param  \Closure|null $testFunction
 * @return \spectrum\core\Spec
 */
function test($name, $multiplier = null, $testFunction = null, $settings = null)
{
	$callBrokerClass = config::getConstructionCommandsCallBrokerClass();
	if ($callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "' . __FUNCTION__ . '" should be call only at declaring state');

	$resultArguments = $callBrokerClass::internal_getArgumentsForTestCommand(func_get_args());
	if ($resultArguments === false)
		throw new \spectrum\constructionCommands\Exception('Incorrect arguments list in construction command "' . __FUNCTION__ . '"');
	else
		list($name, $multiplier, $testFunction, $settings) = $resultArguments;
	
	$specClass = config::getSpecClass();
	$spec = new $specClass();
	$spec->setName($name);
	
	if ($testFunction)
		$spec->testFunction->setFunction($testFunction);
	
	if ($settings)
		$callBrokerClass::internal_setSpecSettings($spec, $settings);
		
	$callBrokerClass::internal_getCurrentDeclaringSpec()->bindChildSpec($spec);
	$callBrokerClass::internal_addMultiplierExclusionSpec($spec);
	
	if (is_array($multiplier))
	{
		$argumentsNumber = 0;
		foreach ($multiplier as $arguments)
		{
			$argumentsNumber++;
			
			if (!is_array($arguments))
				$arguments = array($arguments);
			
			$childSpec = new $specClass();
			$childSpec->setName($callBrokerClass::internal_getNameForArguments($arguments, $argumentsNumber));
			$childSpec->testFunction->setFunctionArguments($arguments);
			$spec->bindChildSpec($childSpec);
		}
	}
	else if (is_object($multiplier) && ($multiplier instanceof \Closure))
	{
		$oldSpec = $callBrokerClass::internal_getCurrentDeclaringSpec();
		$callBrokerClass::internal_setCurrentDeclaringSpec($spec);
		call_user_func($multiplier);
		$callBrokerClass::internal_setCurrentDeclaringSpec($oldSpec);
	}
	
	return $spec;
}