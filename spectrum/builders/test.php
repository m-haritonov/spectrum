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
	if (\spectrum\_internal\isRunningState())
		throw new \spectrum\builders\Exception('Builder "test" should be call only at building state');

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
		throw new \spectrum\builders\Exception('Incorrect arguments in "test" builder');
	else
		list($name, $contexts, $body, $settings) = $arguments;
	
	$specClass = config::getClassReplacement('\spectrum\core\Spec');
	$testSpec = new $specClass();
	
	if ($name !== null)
		$testSpec->setName($name);
	
	if ($body)
		$testSpec->test->setFunction($body);
	
	$settings = \spectrum\_internal\normalizeSettings($settings);
	
	if ($settings['catchPhpErrors'] !== null)
		$testSpec->errorHandling->setCatchPhpErrors($settings['catchPhpErrors']);
	
	if ($settings['breakOnFirstPhpError'] !== null)
		$testSpec->errorHandling->setBreakOnFirstPhpError($settings['breakOnFirstPhpError']);
	
	if ($settings['breakOnFirstMatcherFail'] !== null)
		$testSpec->errorHandling->setBreakOnFirstMatcherFail($settings['breakOnFirstMatcherFail']);
	
	\spectrum\_internal\addExclusionSpec($testSpec);
	\spectrum\_internal\getBuildingSpec()->bindChildSpec($testSpec);
	
	if ($contexts)
	{
		if (is_array($contexts))
		{
			foreach (\spectrum\_internal\convertArrayWithContextsToSpecs($contexts) as $spec)
				$testSpec->bindChildSpec($spec);
		}
		else
			\spectrum\_internal\callFunctionOnBuildingSpec($contexts, $testSpec);	
	}
	
	return $testSpec;
}