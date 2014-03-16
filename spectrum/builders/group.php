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

	$convertArgumentsForSpecFunction = config::getFunctionReplacement('\spectrum\_internal\convertArgumentsForSpec');
	list($name, $contexts, $body, $settings) = $convertArgumentsForSpecFunction(func_get_args(), 'group');
	
	$specClass = config::getClassReplacement('\spectrum\core\Spec');
	$builderSpec = new $specClass();
	
	if ($name !== null)
		$builderSpec->setName($name);

	$setSettingsToSpecFunction = config::getFunctionReplacement('\spectrum\_internal\setSettingsToSpec');
	$setSettingsToSpecFunction($builderSpec, $settings);
	
	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getCurrentBuildingSpec');
	$getCurrentBuildingSpecFunction()->bindChildSpec($builderSpec);

	if ($contexts)
	{
		if (is_array($contexts))
		{
			$contextEndingSpec = new $specClass();
			$convertArrayWithContextsToSpecsFunction = config::getFunctionReplacement('\spectrum\_internal\convertArrayWithContextsToSpecs');
			foreach ($convertArrayWithContextsToSpecsFunction($contexts) as $contextSpec)
			{
				$builderSpec->bindChildSpec($contextSpec);
				$contextSpec->bindChildSpec($contextEndingSpec);
			}
		}
		else
		{
			$callFunctionOnCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\callFunctionOnCurrentBuildingSpec');
			$callFunctionOnCurrentBuildingSpecFunction($contexts, $builderSpec);
			
			$getTestSpecsFunction = config::getFunctionReplacement('\spectrum\_internal\getTestSpecs');
			$testSpecs = $getTestSpecsFunction();
			$contextEndingSpec = new $specClass();
			foreach ($builderSpec->getDescendantEndingSpecs() as $endingSpec)
			{
				if (!in_array($endingSpec, $testSpecs, true))
					$endingSpec->bindChildSpec($contextEndingSpec);
			}
		}
	}
	else
		$contextEndingSpec = $builderSpec;
	
	if ($body)
	{
		$callFunctionOnCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\callFunctionOnCurrentBuildingSpec');
		$callFunctionOnCurrentBuildingSpecFunction($body, $contextEndingSpec);
	}

	return $builderSpec;
}