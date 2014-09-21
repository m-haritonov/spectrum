<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\code\variables;

class objectVar extends \spectrum\core\plugins\reports\drivers\text\components\component {
	static public function getContent($variable, $depth, $inputCharset = null) {
		$properties = static::getProperties($variable);
		
		$output = '';
		$output .= static::getContentForType($properties);
		$output .= static::getContentForClass($variable, $inputCharset);
		$output .= static::callComponentMethod('code\operator', 'getContent', array('{', 'us-ascii')) . static::getOutputNewline();
		$output .= static::getContentForElements($variable, $properties, $depth, $inputCharset);
		
		if (count($properties)) {
			$output .= static::getOutputIndention($depth);
		}
		
		$output .= static::callComponentMethod('code\operator', 'getContent', array('}', 'us-ascii'));
		return $output;
	}
	
	static protected function getContentForType($properties) {
		return static::translate('object') . '(' . count($properties) . ') ';
	}
	
	static protected function getContentForClass($variable, $inputCharset) {
		return '\\' . static::convertToOutputCharset(get_class($variable), $inputCharset) . ' ';
	}
	
	static protected function getContentForElements($variable, $properties, $depth, $inputCharset) {
		$output = '';
		if (count($properties)) {
			foreach ($properties as $key => $value) {
				// Replace full exception trace to light text representation for resource saving
				if ($variable instanceof \Exception && (string) $key === 'trace') {
					$value['value'] = static::convertToOutputCharset($variable->getTraceAsString(), 'utf-8'); // Filenames are come in OS charset (conceivably in "utf-8")
				}
				
				$output .= static::getContentForElement($key, $value, $depth, $inputCharset) . static::getOutputNewline();
			}
		}
		
		return $output;
	}

	static protected function getContentForElement($key, $value, $depth, $inputCharset) {
		return
			static::getOutputIndention($depth + 1) .
			($value['isPublic'] ? static::callComponentMethod('code\keyword', 'getContent', array('public', 'us-ascii')) . ' ' : '') .
			($value['isProtected'] ? static::callComponentMethod('code\keyword', 'getContent', array('protected', 'us-ascii')) . ' ' : '') .
			($value['isPrivate'] ? static::callComponentMethod('code\keyword', 'getContent', array('private', 'us-ascii')) . ' ' : '') .
			($value['isStatic'] ? static::callComponentMethod('code\keyword', 'getContent', array('static', 'us-ascii')) . ' ' : '') .
			static::callComponentMethod('code\operator', 'getContent', array('[', 'us-ascii')) .
			static::convertToOutputCharset($key, $inputCharset) .
			static::callComponentMethod('code\operator', 'getContent', array(']', 'us-ascii')) . ' ' .
			static::callComponentMethod('code\operator', 'getContent', array('=>', 'us-ascii')) . ' ' .
			static::callComponentMethod('code\variable', 'getContent', array($value['value'], $depth + 1, $inputCharset));
	}

	static protected function getProperties($variable) {
		$result = array();
		
		// Use "get_object_vars" because "ReflectionClass::getProperties" does not return undeclared public properties
		foreach (get_object_vars($variable) as $name => $value) {
			$result[$name] = array(
				'value' => $value,
				'isStatic' => false,
				'isPublic' => true,
				'isProtected' => false,
				'isPrivate' => false,
			);
		}
		
		$reflection = new \ReflectionClass($variable);
		$properties = $reflection->getProperties(
			\ReflectionProperty::IS_STATIC |
			\ReflectionProperty::IS_PROTECTED |
			\ReflectionProperty::IS_PRIVATE
		);

		foreach ($properties as $property) {
			$property->setAccessible(true);
			$result[$property->getName()] = array(
				'value' => $property->getValue($variable),
				'isStatic' => $property->isStatic(),
				'isPublic' => $property->isPublic(),
				'isProtected' => $property->isProtected(),
				'isPrivate' => $property->isPrivate(),
			);
		}

		return $result;
	}
}