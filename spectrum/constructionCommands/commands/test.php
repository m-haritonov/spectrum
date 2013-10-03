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
		throw new \spectrum\constructionCommands\Exception('Construction command "' . __FUNCTION__ . '" should be call only at declaring state');

	list($name, $contexts, $body, $settings) = $callBrokerClass::internal_getArgumentsForSpecDeclaringCommand(func_get_args());
	
	$specClass = config::getSpecClass();
	$testSpec = new $specClass();
	
	if ($name !== null)
		$testSpec->setName($name);
	
	if ($body)
		$testSpec->testFunction->setFunction($body);
	
	if ($settings)
		$callBrokerClass::internal_setSpecSettings($testSpec, $settings);
		
	$callBrokerClass::internal_getDeclaringSpec()->bindChildSpec($testSpec);
	$callBrokerClass::internal_addExclusionSpec($testSpec);
	
	if (is_array($contexts) && $contexts)
	{
		foreach ($callBrokerClass::internal_convertArrayContextsToSpecContexts($contexts) as $spec);
			$testSpec->bindChildSpec($spec);
	}
	else if (!is_array($contexts) && $contexts)
		$callBrokerClass::internal_callFunctionOnDeclaringSpec($contexts, $testSpec);
	
	return $testSpec;
}