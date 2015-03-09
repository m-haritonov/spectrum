<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/
namespace spectrum\core\builders;

use spectrum\core\config;
use spectrum\core\Exception;
use spectrum\core\SpecInterface;

/**
 * Creates test.
 * @param null|string|int|float $name
 * @param null|\Closure|array $contexts
 * @param null|\Closure $body
 * @param null|int|bool|array $settings
 * @return \spectrum\core\SpecInterface
 */
function test($name = null, $contexts = null, $body = null, $settings = null) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_private\isRunningState');
	if ($isRunningStateFunction()) {
		throw new Exception('Function "test" should be call only at building state');
	}

	$convertArgumentsForSpecFunction = config::getFunctionReplacement('\spectrum\_private\convertArgumentsForSpec');
	list($name, $contexts, $body, $settings) = $convertArgumentsForSpecFunction(func_get_args(), 'test');
	
	$specClass = config::getClassReplacement('\spectrum\core\Spec');
	/** @var SpecInterface $builderSpec */
	$builderSpec = new $specClass();
	
	if ($name !== null) {
		$builderSpec->setName($name);
	}
	
	if ($body) {
		$builderSpec->getExecutor()->setFunction($body);
	}
	
	$setSettingsToSpecFunction = config::getFunctionReplacement('\spectrum\_private\setSettingsToSpec');
	$setSettingsToSpecFunction($builderSpec, $settings);
	
	$addTestSpecFunction = config::getFunctionReplacement('\spectrum\_private\addTestSpec');
	$addTestSpecFunction($builderSpec);
	
	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_private\getCurrentBuildingSpec');
	$getCurrentBuildingSpecFunction()->bindChildSpec($builderSpec);
	
	if ($contexts) {
		if (is_array($contexts)) {
			$convertArrayWithContextsToSpecsFunction = config::getFunctionReplacement('\spectrum\_private\convertArrayWithContextsToSpecs');
			foreach ($convertArrayWithContextsToSpecsFunction($contexts) as $contextSpec) {
				$builderSpec->bindChildSpec($contextSpec);
			}
		} else {
			$callFunctionOnCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_private\callFunctionOnCurrentBuildingSpec');
			$callFunctionOnCurrentBuildingSpecFunction($contexts, $builderSpec);
		}
	}
	
	return $builderSpec;
}