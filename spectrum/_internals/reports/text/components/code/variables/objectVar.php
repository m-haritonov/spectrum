<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals\reports\text\components\code\variables;

class objectVar extends \spectrum\_internals\reports\text\components\component {
	/**
	 * @param object $variable
	 * @param int $depth
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($variable, $depth, $inputCharset = null) {
		$properties = static::getProperties($variable);
		
		$content = '';
		$content .= static::getContentForType($properties);
		$content .= static::getContentForClass($variable, $inputCharset);
		$content .= static::callComponentMethod('code\operator', 'getContent', array('{', 'us-ascii')) . static::getOutputNewline();
		$content .= static::getContentForElements($variable, $properties, $depth, $inputCharset);
		
		if (count($properties)) {
			$content .= static::getOutputIndention($depth);
		}
		
		$content .= static::callComponentMethod('code\operator', 'getContent', array('}', 'us-ascii'));
		return $content;
	}

	/**
	 * @return string
	 */
	static protected function getContentForType(array $properties) {
		return static::translate('object') . '(' . count($properties) . ') ';
	}

	/**
	 * @param object $variable
	 * @param string $inputCharset
	 * @return string
	 */
	static protected function getContentForClass($variable, $inputCharset) {
		return '\\' . static::convertToOutputCharset(get_class($variable), $inputCharset) . ' ';
	}

	/**
	 * @param object $variable
	 * @param int $depth
	 * @param string $inputCharset
	 * @return string
	 */
	static protected function getContentForElements($variable, array $properties, $depth, $inputCharset) {
		$content = '';
		if (count($properties)) {
			foreach ($properties as $key => $value) {
				// Replace full exception trace to light text representation for resource saving
				if ($variable instanceof \Exception && (string) $key === 'trace') {
					$value['value'] = static::convertToOutputCharset($variable->getTraceAsString(), 'utf-8'); // Filenames are come in OS charset (conceivably in "utf-8")
				}
				
				$content .= static::getContentForElement($key, $value, $depth, $inputCharset) . static::getOutputNewline();
			}
		}
		
		return $content;
	}

	/**
	 * @param mixed $key
	 * @param mixed $value
	 * @param int $depth
	 * @param string $inputCharset
	 * @return string
	 */
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

	/**
	 * @param object $variable
	 * @return array
	 */
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