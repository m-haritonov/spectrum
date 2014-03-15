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
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internal\isRunningState');
	if ($isRunningStateFunction())
		throw new \spectrum\builders\Exception('Builder "test" should be call only at building state');

	$convertArgumentsFunction = config::getFunctionReplacement('\spectrum\_internal\convertArguments');
	$arguments = $convertArgumentsFunction(func_get_args(), array(
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
	
	$specClass = config::getClassReplacement('\spectrum\core\Spec');
	$testSpec = new $specClass();
	
	if ($name !== null)
		$testSpec->setName($name);
	
	if ($body)
		$testSpec->test->setFunction($body);
	
	$normalizeSettingsFunction = config::getFunctionReplacement('\spectrum\_internal\normalizeSettings');
	$settings = $normalizeSettingsFunction($settings);
	
	if ($settings['catchPhpErrors'] !== null)
		$testSpec->errorHandling->setCatchPhpErrors($settings['catchPhpErrors']);
	
	if ($settings['breakOnFirstPhpError'] !== null)
		$testSpec->errorHandling->setBreakOnFirstPhpError($settings['breakOnFirstPhpError']);
	
	if ($settings['breakOnFirstMatcherFail'] !== null)
		$testSpec->errorHandling->setBreakOnFirstMatcherFail($settings['breakOnFirstMatcherFail']);
	
	$addExclusionSpecFunction = config::getFunctionReplacement('\spectrum\_internal\addExclusionSpec');
	$addExclusionSpecFunction($testSpec);
	
	$getBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getBuildingSpec');
	$getBuildingSpecFunction()->bindChildSpec($testSpec);
	
	if ($contexts)
	{
		if (is_array($contexts))
		{
			$convertArrayWithContextsToSpecsFunction = config::getFunctionReplacement('\spectrum\_internal\convertArrayWithContextsToSpecs');
			foreach ($convertArrayWithContextsToSpecsFunction($contexts) as $spec)
				$testSpec->bindChildSpec($spec);
		}
		else
		{
			$callFunctionOnBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\callFunctionOnBuildingSpec');
			$callFunctionOnBuildingSpecFunction($contexts, $testSpec);
		}	
	}
	
	return $testSpec;
}