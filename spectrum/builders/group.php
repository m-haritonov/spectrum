<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;
use spectrum\builders\Exception;

/**
 * @throws \spectrum\builders\Exception If called not at building state
 * @param  string|int|null $name
 * @param  \Closure|array|null $contexts
 * @param  \Closure|null $body
 * @return \spectrum\core\SpecInterface
 */
function group($name = null, $contexts = null, $body = null, $settings = null)
{
	if (\spectrum\_internal\isRunningState())
		throw new Exception('Builder "group" should be call only at building state');

	$arguments = \spectrum\_internal\convertArguments(func_get_args(), array(
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

	$settings = \spectrum\_internal\normalizeSettings($settings);
	
	if ($settings['catchPhpErrors'] !== null)
		$groupSpec->errorHandling->setCatchPhpErrors($settings['catchPhpErrors']);
	
	if ($settings['breakOnFirstPhpError'] !== null)
		$groupSpec->errorHandling->setBreakOnFirstPhpError($settings['breakOnFirstPhpError']);
	
	if ($settings['breakOnFirstMatcherFail'] !== null)
		$groupSpec->errorHandling->setBreakOnFirstMatcherFail($settings['breakOnFirstMatcherFail']);
	
	\spectrum\_internal\getBuildingSpec()->bindChildSpec($groupSpec);

	if ($contexts)
	{
		if (is_array($contexts))
		{
			$contextEndingSpec = new $specClass();
			foreach (\spectrum\_internal\convertArrayWithContextsToSpecs($contexts) as $spec)
			{
				$groupSpec->bindChildSpec($spec);
				$spec->bindChildSpec($contextEndingSpec);
			}
		}
		else
		{
			\spectrum\_internal\callFunctionOnBuildingSpec($contexts, $groupSpec);
			
			$contextEndingSpec = new $specClass();
			foreach (\spectrum\_internal\filterOutExclusionSpecs($groupSpec->getEndingSpecs()) as $endingSpec)
				$endingSpec->bindChildSpec($contextEndingSpec);
		}
	}
	else
		$contextEndingSpec = $groupSpec;
	
	if ($body)
		\spectrum\_internal\callFunctionOnBuildingSpec($body, $contextEndingSpec);

	return $groupSpec;
}