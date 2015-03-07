<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;
use spectrum\core\SpecInterface;
use spectrum\Exception;

/**
 * Creates group.
 * @param null|string|int|float $name
 * @param null|\Closure|array $contexts
 * @param null|\Closure $body
 * @param null|int|bool|array $settings
 * @return \spectrum\core\SpecInterface
 */
function group($name = null, $contexts = null, $body = null, $settings = null) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_private\isRunningState');
	if ($isRunningStateFunction()) {
		throw new Exception('Builder "group" should be call only at building state');
	}

	$convertArgumentsForSpecFunction = config::getFunctionReplacement('\spectrum\_private\convertArgumentsForSpec');
	list($name, $contexts, $body, $settings) = $convertArgumentsForSpecFunction(func_get_args(), 'group');
	
	$specClass = config::getClassReplacement('\spectrum\core\Spec');
	/** @var SpecInterface $builderSpec */
	$builderSpec = new $specClass();
	
	if ($name !== null) {
		$builderSpec->setName($name);
	}

	$setSettingsToSpecFunction = config::getFunctionReplacement('\spectrum\_private\setSettingsToSpec');
	$setSettingsToSpecFunction($builderSpec, $settings);
	
	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_private\getCurrentBuildingSpec');
	$getCurrentBuildingSpecFunction()->bindChildSpec($builderSpec);

	if ($contexts) {
		if (is_array($contexts)) {
			$contextEndingSpec = new $specClass();
			$convertArrayWithContextsToSpecsFunction = config::getFunctionReplacement('\spectrum\_private\convertArrayWithContextsToSpecs');
			foreach ($convertArrayWithContextsToSpecsFunction($contexts) as $contextSpec) {
				/** @var SpecInterface $contextSpec */
				$builderSpec->bindChildSpec($contextSpec);
				$contextSpec->bindChildSpec($contextEndingSpec);
			}
		} else {
			$callFunctionOnCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_private\callFunctionOnCurrentBuildingSpec');
			$callFunctionOnCurrentBuildingSpecFunction($contexts, $builderSpec);
			
			$getTestSpecsFunction = config::getFunctionReplacement('\spectrum\_private\getTestSpecs');
			$testSpecs = $getTestSpecsFunction();
			/** @var SpecInterface $contextEndingSpec */
			$contextEndingSpec = new $specClass();
			foreach ($builderSpec->getDescendantEndingSpecs() as $endingSpec) {
				if (!in_array($endingSpec, $testSpecs, true)) {
					$endingSpec->bindChildSpec($contextEndingSpec);
				}
			}
		}
	} else {
		$contextEndingSpec = $builderSpec;
	}
	
	if ($body) {
		$callFunctionOnCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_private\callFunctionOnCurrentBuildingSpec');
		$callFunctionOnCurrentBuildingSpecFunction($body, $contextEndingSpec);
	}

	return $builderSpec;
}