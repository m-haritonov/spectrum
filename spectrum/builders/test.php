<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;

/**
 * @throws \spectrum\builders\Exception If called not at building state or if data provider is bad
 * @param  string|int|null $name
 * @param  \Closure|array|null $contexts
 * @param  \Closure|null $body
 * @return \spectrum\core\Spec
 */
function test($name = null, $contexts = null, $body = null, $settings = null)
{
	if (\spectrum\builders\isRunningState())
		throw new \spectrum\builders\Exception('Builder "test" should be call only at building state');

	$arguments = \spectrum\builders\internal\convertArguments(func_get_args(), array(
		array('closure:body'),                                                                                  // function(\Closure $body)
		array('closure:body', 'null|scalar|array:settings'),                                                    // function(\Closure $body, null|scalar|array $settings)
		array('array|closure:contexts', 'closure:body'),                                                        // function(array|\Closure $contexts, \Closure $body)
		array('array|closure:contexts', 'closure:body', 'null|scalar|array:settings'),                          // function(array|\Closure $contexts, \Closure $body, null|scalar|array $settings)
		array('null|scalar:name', 'closure:body'),                                                              // function(null|scalar $name, \Closure $body)
		array('null|scalar:name', 'closure:body', 'null|scalar|array:settings'),                                // function(null|scalar $name, \Closure $body, null|scalar|array $settings)
		array('null|scalar:name', 'null|array|closure:contexts', 'closure:body'),                               // function(null|scalar $name, null|array|\Closure $contexts, \Closure $body)
		array('null|scalar:name', 'null|array|closure:contexts', 'closure:body', 'null|scalar|array:settings'), // function(null|scalar $name, null|array|\Closure $contexts, \Closure $body, null|scalar|array $settings)
	), array(
		'name' => null,
		'contexts' => null,
		'body' => null,
		'settings' => null,
	));
	
	if ($arguments === null)
		throw new \spectrum\builders\Exception('Incorrect arguments in "test" builder');
	else
		list($name, $contexts, $body, $settings) = $arguments;
	
	$specClass = config::getSpecClass();
	$testSpec = new $specClass();
	
	if ($name !== null)
		$testSpec->setName($name);
	
	if ($body)
		$testSpec->test->setFunction($body);
	
	$settings = \spectrum\builders\internal\normalizeSettings($settings);
	
	if ($settings['catchPhpErrors'] !== null)
		$testSpec->errorHandling->setCatchPhpErrors($settings['catchPhpErrors']);
	
	if ($settings['breakOnFirstPhpError'] !== null)
		$testSpec->errorHandling->setBreakOnFirstPhpError($settings['breakOnFirstPhpError']);
	
	if ($settings['breakOnFirstMatcherFail'] !== null)
		$testSpec->errorHandling->setBreakOnFirstMatcherFail($settings['breakOnFirstMatcherFail']);
	
	\spectrum\builders\internal\addExclusionSpec($testSpec);
	\spectrum\builders\internal\getBuildingSpec()->bindChildSpec($testSpec);
	
	if ($contexts)
	{
		if (is_array($contexts))
		{
			foreach (\spectrum\builders\internal\convertArrayWithContextsToSpecs($contexts) as $spec)
				$testSpec->bindChildSpec($spec);
		}
		else
			\spectrum\builders\internal\callFunctionOnBuildingSpec($contexts, $testSpec);	
	}
	
	return $testSpec;
}