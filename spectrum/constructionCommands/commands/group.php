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
 * Support params variants:
 * group()
 * group(scalar $name)
 * group(scalar $name, \Closure $function)
 * group(scalar $name, \Closure $multiplier, \Closure $function)
 * group(scalar $name, \Closure $function, array $settings)
 * group(scalar $name, \Closure $multiplier, \Closure $function, array $settings)
 * group(scalar $name, array $settings)
 * group(\Closure $function)
 * group(\Closure $multiplier, \Closure $function)
 * group(\Closure $function, array $settings)
 * group(\Closure $multiplier, \Closure $function, array $settings)
 * group(array $settings)
 * 
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state
 * @return \spectrum\core\SpecInterface
 */
function group($name = null, $multiplier = null, $function = null, $settings = null)
{
	$callBrokerClass = config::getConstructionCommandsCallBrokerClass();
	if ($callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "' . __FUNCTION__ . '" should be call only at declaring state');

	$resultArguments = $callBrokerClass::internal_getArgumentsForGroupCommand(func_get_args());
	if ($resultArguments === false)
		throw new \spectrum\constructionCommands\Exception('Incorrect arguments list in construction command "' . __FUNCTION__ . '"');
	else
		list($name, $multiplier, $function, $settings) = $resultArguments;
	
	$specClass = config::getSpecClass();
	$spec = new $specClass();
	
	if ($name !== null)
		$spec->setName($name);

	if ($settings)
		$callBrokerClass::internal_setSpecSettings($spec, $settings);

	$callBrokerClass::internal_getCurrentDeclaringSpec()->bindChildSpec($spec);

	if ($multiplier)
	{
		$oldSpec = $callBrokerClass::internal_getCurrentDeclaringSpec();
		$callBrokerClass::internal_setCurrentDeclaringSpec($spec);
		call_user_func($multiplier);
		$callBrokerClass::internal_setCurrentDeclaringSpec($oldSpec);
		
		$unionSpec = new $specClass();
		foreach ($callBrokerClass::internal_getMultiplierEndingSpecs($spec) as $endingSpec)
			$endingSpec->bindChildSpec($unionSpec);
		
		$spec = $unionSpec;
	}
	
	if ($function)
	{
		$oldSpec = $callBrokerClass::internal_getCurrentDeclaringSpec();
		$callBrokerClass::internal_setCurrentDeclaringSpec($spec);
		call_user_func($function);
		$callBrokerClass::internal_setCurrentDeclaringSpec($oldSpec);
	}

	return $spec;
}