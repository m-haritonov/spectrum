<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\config;
use spectrum\core\SpecInterface;
use spectrum\core\Exception;

/**
 * @access private
 * @return SpecInterface[]
 */
function convertArrayWithContextsToSpecs(array $contexts) {
	$specClass = config::getClassReplacement('\spectrum\core\Spec');
	$specs = array();
	$function = function(){};
	
	$num = 0;
	foreach ($contexts as $title => $values) {
		$num++;
		if (is_array($values)) {
			$getArrayWithContextsElementTitleFunction = config::getFunctionReplacement('\spectrum\core\_private\getArrayWithContextsElementTitle');
			$title = $getArrayWithContextsElementTitleFunction($title, $values);
			
			$contextModifierFunction = function() use($values){
				$getCurrentDataFunction = config::getFunctionReplacement('\spectrum\core\_private\getCurrentData');
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
		$spec->getContextModifiers()->add($contextModifierFunction, 'before');
		$specs[] = $spec;
	}
	
	return $specs;
}