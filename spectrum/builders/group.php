<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;
use spectrum\Exception;

/**
 * @throws \spectrum\Exception If called not at building state
 * @param  string|int|null $name
 * @param  \Closure|array|null $contexts
 * @param  \Closure|null $body
 * @return \spectrum\core\SpecInterface
 */
function group($name = null, $contexts = null, $body = null, $settings = null)
{
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internal\isRunningState');
	if ($isRunningStateFunction())
		throw new Exception('Builder "group" should be call only at building state');

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
		throw new Exception('Incorrect arguments in "group" builder');
	else
		list($name, $contexts, $body, $settings) = $arguments;
	
	$specClass = config::getClassReplacement('\spectrum\core\Spec');
	$groupSpec = new $specClass();
	
	if ($name !== null)
		$groupSpec->setName($name);

	$normalizeSettingsFunction = config::getFunctionReplacement('\spectrum\_internal\normalizeSettings');
	$settings = $normalizeSettingsFunction($settings);
	
	if ($settings['catchPhpErrors'] !== null)
		$groupSpec->errorHandling->setCatchPhpErrors($settings['catchPhpErrors']);
	
	if ($settings['breakOnFirstPhpError'] !== null)
		$groupSpec->errorHandling->setBreakOnFirstPhpError($settings['breakOnFirstPhpError']);
	
	if ($settings['breakOnFirstMatcherFail'] !== null)
		$groupSpec->errorHandling->setBreakOnFirstMatcherFail($settings['breakOnFirstMatcherFail']);
	
	$getBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getBuildingSpec');
	$getBuildingSpecFunction()->bindChildSpec($groupSpec);

	if ($contexts)
	{
		if (is_array($contexts))
		{
			$contextEndingSpec = new $specClass();
			$convertArrayWithContextsToSpecsFunction = config::getFunctionReplacement('\spectrum\_internal\convertArrayWithContextsToSpecs');
			foreach ($convertArrayWithContextsToSpecsFunction($contexts) as $spec)
			{
				$groupSpec->bindChildSpec($spec);
				$spec->bindChildSpec($contextEndingSpec);
			}
		}
		else
		{
			$callFunctionOnBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\callFunctionOnBuildingSpec');
			$callFunctionOnBuildingSpecFunction($contexts, $groupSpec);
			
			$contextEndingSpec = new $specClass();
			$filterOutExclusionSpecsFunction = config::getFunctionReplacement('\spectrum\_internal\filterOutExclusionSpecs');
			foreach ($filterOutExclusionSpecsFunction($groupSpec->getEndingSpecs()) as $endingSpec)
				$endingSpec->bindChildSpec($contextEndingSpec);
		}
	}
	else
		$contextEndingSpec = $groupSpec;
	
	if ($body)
	{
		$callFunctionOnBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\callFunctionOnBuildingSpec');
		$callFunctionOnBuildingSpecFunction($body, $contextEndingSpec);
	}

	return $groupSpec;
}