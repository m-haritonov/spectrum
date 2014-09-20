<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/
namespace spectrum\builders;

use spectrum\config;
use spectrum\Exception;

/**
 * @param  string|int|null $name
 * @param  \Closure|array|null $contexts
 * @param  \Closure|null $body
 * @return \spectrum\core\SpecInterface
 */
function test($name = null, $contexts = null, $body = null, $settings = null) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internals\isRunningState');
	if ($isRunningStateFunction()) {
		throw new Exception('Builder "test" should be call only at building state');
	}

	$convertArgumentsForSpecFunction = config::getFunctionReplacement('\spectrum\_internals\convertArgumentsForSpec');
	list($name, $contexts, $body, $settings) = $convertArgumentsForSpecFunction(func_get_args(), 'test');
	
	$specClass = config::getClassReplacement('\spectrum\core\Spec');
	$builderSpec = new $specClass();
	
	if ($name !== null) {
		$builderSpec->setName($name);
	}
	
	if ($body) {
		$builderSpec->test->setFunction($body);
	}
	
	$setSettingsToSpecFunction = config::getFunctionReplacement('\spectrum\_internals\setSettingsToSpec');
	$setSettingsToSpecFunction($builderSpec, $settings);
	
	$addTestSpecFunction = config::getFunctionReplacement('\spectrum\_internals\addTestSpec');
	$addTestSpecFunction($builderSpec);
	
	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internals\getCurrentBuildingSpec');
	$getCurrentBuildingSpecFunction()->bindChildSpec($builderSpec);
	
	if ($contexts) {
		if (is_array($contexts)) {
			$convertArrayWithContextsToSpecsFunction = config::getFunctionReplacement('\spectrum\_internals\convertArrayWithContextsToSpecs');
			foreach ($convertArrayWithContextsToSpecsFunction($contexts) as $contextSpec) {
				$builderSpec->bindChildSpec($contextSpec);
			}
		} else {
			$callFunctionOnCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internals\callFunctionOnCurrentBuildingSpec');
			$callFunctionOnCurrentBuildingSpecFunction($contexts, $builderSpec);
		}
	}
	
	return $builderSpec;
}