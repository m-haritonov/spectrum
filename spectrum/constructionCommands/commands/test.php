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
 * Support params variants: see "\spectrum\constructionCommands\commands\internal\getArgumentsForSpecDeclaringCommand" 
 * function.
 *
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state or if data provider is bad
 * @param  string|int|null $name
 * @param  \Closure|array|null $contexts
 * @param  \Closure|null $body
 * @return \spectrum\core\Spec
 */
function test($storage, $name = null, $contexts = null, $body = null, $settings = null)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	if ($callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "test" should be call only at declaring state');

	$arguments = $callBrokerClass::internal_getArgumentsForSpecDeclaringCommand(array_slice(func_get_args(), 1));
	if ($arguments === null)
		throw new \spectrum\constructionCommands\Exception('Incorrect arguments in "test" command');
	else
		list($name, $contexts, $body, $settings) = $arguments;
	
	$specClass = config::getSpecClass();
	$testSpec = new $specClass();
	
	if ($name !== null)
		$testSpec->setName($name);
	
	if ($body)
		$testSpec->testFunction->setFunction($body);
	
	if ($settings !== null)
		$callBrokerClass::internal_setSpecSettings($testSpec, $settings);

	$callBrokerClass::internal_addExclusionSpec($testSpec);
	$callBrokerClass::internal_getDeclaringSpec()->bindChildSpec($testSpec);
	
	if ($contexts)
	{
		if (is_array($contexts))
		{
			foreach ($callBrokerClass::internal_convertArrayContextsToSpecContexts($contexts) as $spec)
				$testSpec->bindChildSpec($spec);
		}
		else
			$callBrokerClass::internal_callFunctionOnDeclaringSpec($contexts, $testSpec);	
	}
	
	return $testSpec;
}