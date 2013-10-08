<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands;

use spectrum\config;
use spectrum\constructionCommands\Exception;

/**
 * Support params variants: see "\spectrum\constructionCommands\commands\internal\getArgumentsForSpecDeclaringCommand" 
 * function.
 * 
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state
 * @param  string|int|null $name
 * @param  \Closure|array|null $contexts
 * @param  \Closure|null $body
 * @return \spectrum\core\SpecInterface
 */
function group($storage, $name = null, $contexts = null, $body = null, $settings = null)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	if ($callBrokerClass::internal_isRunningState())
		throw new Exception('Construction command "group" should be call only at declaring state');

	$arguments = $callBrokerClass::internal_getArgumentsForSpecDeclaringCommand(array_slice(func_get_args(), 1));
	if ($arguments === null)
		throw new Exception('Incorrect arguments in "group" command');
	else
		list($name, $contexts, $body, $settings) = $arguments;
	
	$specClass = config::getSpecClass();
	$groupSpec = new $specClass();
	
	if ($name !== null)
		$groupSpec->setName($name);

	if ($settings !== null)
		$callBrokerClass::internal_setSpecSettings($groupSpec, $settings);

	$callBrokerClass::internal_getDeclaringSpec()->bindChildSpec($groupSpec);

	if ($contexts)
	{
		if (is_array($contexts))
		{
			$contextEndingSpec = new $specClass();
			foreach ($callBrokerClass::internal_convertArrayContextsToSpecContexts($contexts) as $spec)
			{
				$groupSpec->bindChildSpec($spec);
				$spec->bindChildSpec($contextEndingSpec);
			}
		}
		else
		{
			$callBrokerClass::internal_callFunctionOnDeclaringSpec($contexts, $groupSpec);
			
			$contextEndingSpec = new $specClass();
			foreach ($callBrokerClass::internal_filterOutExclusionSpecs($groupSpec->getEndingSpecs()) as $endingSpec)
				$endingSpec->bindChildSpec($contextEndingSpec);
		}
	}
	else
		$contextEndingSpec = $groupSpec;
	
	if ($body)
		$callBrokerClass::internal_callFunctionOnDeclaringSpec($body, $contextEndingSpec);

	return $groupSpec;
}