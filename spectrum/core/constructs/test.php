<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/
namespace spectrum\core\constructs;

use spectrum\core\config;
use spectrum\core\Exception;
use spectrum\core\models\SpecInterface;

/**
 * Creates test.
 * @param null|string|int|float $name
 * @param null|\Closure|array $contexts
 * @param null|\Closure $body
 * @param null|int|bool|array $settings
 * @return \spectrum\core\models\SpecInterface
 */
function test($name = null, $contexts = null, $body = null, $settings = null) {
	$isRunningStateFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\isRunningState');
	if ($isRunningStateFunction()) {
		throw new Exception('Function "test" should be call only at building state');
	}

	$convertArgumentsForSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\convertArgumentsForSpec');
	list($name, $contexts, $body, $settings) = $convertArgumentsForSpecFunction(func_get_args(), 'test');
	
	$specClass = config::getCoreClassReplacement('\spectrum\core\models\Spec');
	/** @var SpecInterface $constructSpec */
	$constructSpec = new $specClass();
	
	if ($name !== null) {
		$constructSpec->setName($name);
	}
	
	if ($body) {
		$constructSpec->getExecutor()->setFunction($body);
	}
	
	$setSettingsToSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\setSettingsToSpec');
	$setSettingsToSpecFunction($constructSpec, $settings);
	
	$addTestSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\addTestSpec');
	$addTestSpecFunction($constructSpec);
	
	$getCurrentBuildingSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\getCurrentBuildingSpec');
	$getCurrentBuildingSpecFunction()->bindChildSpec($constructSpec);
	
	if ($contexts) {
		if (is_array($contexts)) {
			$convertArrayWithContextsToSpecsFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\convertArrayWithContextsToSpecs');
			foreach ($convertArrayWithContextsToSpecsFunction($contexts) as $contextSpec) {
				$constructSpec->bindChildSpec($contextSpec);
			}
		} else {
			$callFunctionOnCurrentBuildingSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\callFunctionOnCurrentBuildingSpec');
			$callFunctionOnCurrentBuildingSpecFunction($contexts, $constructSpec);
		}
	}
	
	return $constructSpec;
}