<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;

use spectrum\config;
use spectrum\core\SpecInterface;
use spectrum\Exception;

/**
 * @access private
 * @return array
 */
function convertArrayWithContextsToSpecs(array $contexts) {
	$specClass = config::getClassReplacement('\spectrum\core\Spec');
	$specs = array();
	$function = function(){};
	
	$num = 0;
	foreach ($contexts as $title => $values) {
		$num++;
		if (is_array($values)) {
			$getArrayWithContextsElementTitleFunction = config::getFunctionReplacement('\spectrum\_internals\getArrayWithContextsElementTitle');
			$title = $getArrayWithContextsElementTitleFunction($title, $values);
			
			$contextModifierFunction = function() use($values){
				$getCurrentDataFunction = config::getFunctionReplacement('\spectrum\_internals\getCurrentData');
				$data = $getCurrentDataFunction();
				foreach ($values as $propertyName => $value) {
					$data->$propertyName = $value;
				}
			};
		} else if ($values instanceof $function) {
			$contextModifierFunction = $values;
		} else {
			throw new Exception('The context row #' . $num . ' should be an array');
		}
		
		/** @var SpecInterface $spec */
		$spec = new $specClass();
		$spec->setName($title);
		$spec->contextModifiers->add($contextModifierFunction, 'before');
		$specs[] = $spec;
	}
	
	return $specs;
}