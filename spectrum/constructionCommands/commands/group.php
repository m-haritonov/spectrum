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
 * Support params variants: see "\spectrum\constructionCommands\commands\internal\getArgumentsForSpecDeclaringCommand" 
 * function.
 * 
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state
 * @param  string|int|null $name
 * @param  \Closure|array|null $contexts
 * @param  \Closure|null $body
 * @return \spectrum\core\Spec
 */
function group($storage, $name = null, $contexts = null, $body = null, $settings = null)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	if ($callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "' . __FUNCTION__ . '" should be call only at declaring state');

	$resultArguments = $callBrokerClass::internal_getArgumentsForGroupCommand(func_get_args());
	if ($resultArguments === null)
		throw new \spectrum\constructionCommands\Exception('Incorrect arguments list in construction command "' . __FUNCTION__ . '"');
	else
		list($name, $contexts, $body, $settings) = $resultArguments;
	
	$specClass = config::getSpecClass();
	$groupSpec = new $specClass();
	
	if ($name !== null)
		$groupSpec->setName($name);

	if ($settings)
		$callBrokerClass::internal_setSpecSettings($groupSpec, $settings);

	$callBrokerClass::internal_getDeclaringSpec()->bindChildSpec($groupSpec);

	if (is_array($contexts) && $contexts)
	{
		$unionEndingSpec = new $specClass();
		foreach ($callBrokerClass::internal_convertContextArrayToSpecs($contexts) as $spec);
		{
			$groupSpec->bindChildSpec($spec);
			$spec->bindChildSpec($unionEndingSpec);
		}
		
		$groupSpec = $unionEndingSpec;
	}
	else if (!is_array($contexts) && $contexts)
	{
		$callBrokerClass::internal_callFunctionOnDeclaringSpec($contexts, $groupSpec);
		
		$unionEndingSpec = new $specClass();
		foreach ($callBrokerClass::internal_filterOutExclusionSpecs($groupSpec->getEndingSpecs()) as $endingSpec)
			$endingSpec->bindChildSpec($unionEndingSpec);
		
		$groupSpec = $unionEndingSpec;
	}
	
	if ($body)
		$callBrokerClass::internal_callFunctionOnDeclaringSpec($body, $groupSpec);

	return $groupSpec;
}