<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\_testware;

use spectrum\core\SpecInterface;

require_once __DIR__ . '/../init.php';

class tools {
	static public $temp;
	static private $classNumber = 0;
	static private $classStaticPropertyBackups = array();
	static private $objectPropertyBackups = array();

	static public function backupClassStaticProperties($className) {
		$reflection = new \ReflectionClass($className);
		static::$classStaticPropertyBackups[$className] = $reflection->getStaticProperties();
	}

	static public function restoreClassStaticProperties($className) {
		foreach (static::$classStaticPropertyBackups[$className] as $name => $value) {
			$propertyReflection = new \ReflectionProperty($className, $name);
			$propertyReflection->setAccessible(true);
			$propertyReflection->setValue(null, $value);
		}
	}
	
	static public function backupObjectProperties($object) {
		var_dump(serialize($object));
		static::$objectPropertyBackups[spl_object_hash($object)] = serialize($object);
	}

	static public function restoreObjectProperties($object) {
		$objectReflection = new \ReflectionClass($object);
		
		$backupObject = unserialize(static::$objectPropertyBackups[spl_object_hash($object)]);
		$backupObjectReflection = new \ReflectionClass($backupObject);
		
		foreach ($objectReflection->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE) as $objectProperty) {
			$objectProperty->setAccessible(true);
			
			$backupObjectProperty = $backupObjectReflection->getProperty($objectProperty->getName());
			$backupObjectProperty->setAccessible(true);
			
			$objectProperty->setValue($object, $backupObjectProperty->getValue($backupObject));
		}
	}
	
	static public function getUniqueArrayElements(array $array, $preserveKeys = true) {
		$newArray = array();
		foreach ($array as $key => $element) {
			if (!in_array($element, $newArray, true)) {
				if ($preserveKeys) {
					$newArray[$key] = $element;
				} else {
					$newArray[] = $element;
				}
			}
		}
		
		return $newArray;
	}
	
	static public function getLastErrorHandler() {
		$lastErrorHandler = set_error_handler(function($errorSeverity, $errorMessage){});
		restore_error_handler();
		return $lastErrorHandler;
	}

	/**
	 * @return string Class name string in "US-ASCII" charset
	 */
	static public function createClass($code) {
		$namespace = 'spectrum\tests\_testware\_dynamicClasses_';
		$className = 'DynamicClass' . self::$classNumber;
		self::$classNumber++;
		
		$code = preg_replace(
			'/^((\s*abstract|\s*final)+)*\s*class\s*\.\.\./is',
			'namespace ' . $namespace . '; $1 class ' . $className . ' ',
			$code
		);
		
		eval($code);
		return '\\' . $namespace . '\\' . $className;
	}
	
	static public function createInterface($code) {
		$namespace = 'spectrum\tests\_testware\_dynamicClasses_';
		$className = 'DynamicClass' . self::$classNumber;
		self::$classNumber++;
		
		$code = preg_replace(
			'/^\s*interface\s*\.\.\./is',
			'namespace ' . $namespace . '; interface ' . $className . ' ',
			$code
		);
		
		eval($code);
		return '\\' . $namespace . '\\' . $className;
	}
	
/**/

	/**
	 * Format:
	 * elements
	 * relations
	 * elements
	 * relations
	 * ...
	 * elements
	 * 
	 * Spaces between elements are required (count of spaces is not important).
	 * Spaces between relations are not required.
	 * Leading and ending underscore chars ("_") are removed from element names. 
	 * 
	 * Relations:
	 * "/" - open child of direct group or close child of reversed group
	 * "|" - single child or child in opened group
	 * "\" - close child of direct group or open child of reversed group
	 * "." - no children
	 * "+" - previous child
	 * 
	 * Direct group (lower elements is adding to upper elements):
	 *    0
	 *  / | \
	 * 1  2 3
	 * 
	 * Reversed group (upper elements is adding to lower elements):
	 * 0 1  2
	 * \ | /
	 *   3
	 * 
	 * === Examples ===
	 * 
	 * Example 1:
	 *     __0__
	 *    /  |  \
	 *   1   2  3
	 *  / \  |
	 * 4  5 aaa
	 * 
	 * Returns: array(
	 *     '0' => new Spec(),
	 *     '1' => new Spec(),
	 *     '2' => new Spec(),
	 *     '3' => new Spec(),
	 *     '4' => new Spec(),
	 *     '5' => new Spec(),
	 *     'aaa' => new Spec(),
	 * )
	 * 
	 * Example 2 (element "2" has no children, element "3" has one child):
	 *     ___0___
	 *    / |  |  \
	 *   1  2  3  4
	 *  / \ .  |
	 * 5  6    7
	 * 
	 * Example 3 (element "2" has two parents):
	 * 0   1
	 * \  /
	 *  2
	 * 
	 * Example 4 (element "5" has three parents, element "3" has no children): 
	 *     ___0___
	 *    / |  |  \
	 *   1  2  3  4
	 *   \  |  . /
	 *       5
	 * 
	 * Example 5 (element "6" has two parents):
	 *     ____0___
	 *    /  |  |  \
	 *   1   2  3  4
	 *  / \+/ \
	 * 5   6  7
	 * 
	 * See "self::providerCreateSpecsByVisualPattern" method for more examples.
	 * 
	 * @return SpecInterface[]
	 */
	static public function createSpecsByVisualPattern($pattern, array $additionalRelations = array(), $specClass = '\spectrum\core\Spec') {
		$specs = array();
		$lines = preg_split('/[\r\n]+/s', trim($pattern));
		
		// Process element lines
		foreach ($lines as $lineIndex => $line) {
			if ($lineIndex % 2 != 0) {
				continue;
			}
			
			$elements = preg_split('/\s+/s', trim($line));
			foreach ($elements as $elementIndex => $elementName) {
				$elementName = preg_replace('/^_+|_+$/s', '', $elementName);
				$elements[$elementIndex] = $elementName;
				
				if (array_key_exists($elementName, $specs)) {
					throw new \Exception('Duplicate name is present on line ' . ($lineIndex + 1));
				}

				$specs[$elementName] = new $specClass();
			}
			
			$lines[$lineIndex] = $elements;
		}
		
		// Process relation lines
		foreach ($lines as $lineIndex => $line) {
			if ($lineIndex % 2 == 0) {
				continue;
			}
			
			$upperElements = $lines[$lineIndex - 1];
			$lastUpperElementIndex = count($upperElements) - 1;
			
			$lowerElements = $lines[$lineIndex + 1];
			$lastLowerElementIndex = count($lowerElements) - 1;
			
			$relations = preg_replace('/\s+/s', '', $line);
			$relationLength = mb_strlen($relations, 'us-ascii');
			
			$currentUpperElementIndex = 0;
			$currentLowerElementIndex = 0;
			
			$openedGroup = null;
			
			for ($i = 0; $i < $relationLength; $i++) {
				$relation = (string) $relations[$i];
				if ($relation === '+') {
					$currentLowerElementIndex--;
				} else if ($currentUpperElementIndex > $lastUpperElementIndex || $currentLowerElementIndex > $lastLowerElementIndex) {
					break;
				} else if ($relation === '.') {
					$currentUpperElementIndex++;
				} else if ($relation === '/') {
					if (!$openedGroup) {
						$openedGroup = 'direct';
					}
					
					$specs[$upperElements[$currentUpperElementIndex]]->bindChildSpec($specs[$lowerElements[$currentLowerElementIndex]]);
					
					if ($openedGroup === 'direct') {
						$currentLowerElementIndex++;
					} else {
						$currentUpperElementIndex++;
						$currentLowerElementIndex++;
						$openedGroup = null;
					}
				} else if ($relation === '|') {
					$specs[$upperElements[$currentUpperElementIndex]]->bindChildSpec($specs[$lowerElements[$currentLowerElementIndex]]);
					
					if ($openedGroup === 'direct') {
						$currentLowerElementIndex++;
					} else if ($openedGroup === 'reversed') {
						$currentUpperElementIndex++;
					} else {
						$currentUpperElementIndex++;
						$currentLowerElementIndex++;
					}
				} else if ($relation === '\\') {
					if (!$openedGroup) {
						$openedGroup = 'reversed';
					}
					
					$specs[$upperElements[$currentUpperElementIndex]]->bindChildSpec($specs[$lowerElements[$currentLowerElementIndex]]);
					
					if ($openedGroup === 'reversed') {
						$currentUpperElementIndex++;
					} else {
						$currentUpperElementIndex++;
						$currentLowerElementIndex++;
						$openedGroup = null;
					}
				} else {
					throw new \Exception('Unknown relation "' . $relation . '" is present on line ' . ($lineIndex + 1));
				}
			}
		}
		
		foreach ($additionalRelations as $parentSpecName => $childrenSpecNames) {
			foreach ((array) $childrenSpecNames as $childSpecName) {
				$specs[$parentSpecName]->bindChildSpec($specs[$childSpecName]);
			}
		}
		
		return $specs;
	}

	/**
	 * @deprecated Use "self::createSpecsByVisualPattern" method
	 * 
	 * Example 1 (add parent specs):
	 * ->Spec
	 * ->->Spec
	 * ->Spec
	 * Spec(name)
	 * 
	 * Example 2 (add child specs):
	 * Spec(name)
	 * ->Spec
	 * ->->Spec
	 * ->Spec
	 * 
	 * Example 3 (add parent and child specs):
	 * ->Spec
	 * ->->Spec
	 * ->Spec
	 * Spec(name)
	 * ->Spec
	 * ->->Spec
	 * ->Spec
	 * 
	 * @param $additionalRelations Example:
	 *                      array(
	 *                          'name' => array(4, 5, 6, 'aaa'),
	 *                          4 => array('bbb', 'ccc'),
	 *                          5 => array('zzz'),
	 *                      );
	 * 
	 * @return SpecInterface[]
	 */
	static public function createSpecsByListPattern($pattern, array $additionalRelations = array()) {
		$pattern = trim($pattern);
		
		if (preg_match('/^\-\>/is', $pattern)) {
			$isReverse = true;
		} else {
			$isReverse = false;
		}

		$specsWithNames = array();
		$specsWithDepths = array();
		
		foreach (preg_split("/\r?\n/s", $pattern) as $key => $row) {
			list($depth, $className, $name) = static::parseSpecTreeRow($row);
			
			if ($depth == 0) {
				$isReverse = false;
			} else if ($isReverse) {
				$depth = -$depth;
			}
			
			$className = '\spectrum\core\\' . $className;
			
			if ($name == '') {
				$name = $key;
			}
			
			if (array_key_exists($name, $specsWithNames)) {
				throw new \Exception('Name "' . $name . '" is already used');
			}
			
			$spec = new $className();
			$specsWithNames[$name] = $spec;
			$specsWithDepths[] = array('spec' => $spec, 'depth' => $depth);
		}

		foreach ($specsWithDepths as $key => $item) {
			if ($item['depth'] < 0) {
				$masterItem = static::getNextItemWithMasterDepth($key, $specsWithDepths);
				
				if (!$masterItem) {
					throw new \Exception('Depth can not jump more than one');
				}
				
				$masterItem['spec']->bindParentSpec($item['spec']);
			} else if ($item['depth'] > 0) {
				$masterItem = static::getPrevItemWithMasterDepth($key, $specsWithDepths);
				
				if (!$masterItem) {
					throw new \Exception('Depth can not jump more than one');
				}
				
				$masterItem['spec']->bindChildSpec($item['spec']);
			}
		}
		
		foreach ($additionalRelations as $parentSpecName => $childrenSpecNames) {
			foreach ((array) $childrenSpecNames as $childSpecName) {
				$specsWithNames[$parentSpecName]->bindChildSpec($specsWithNames[$childSpecName]);
			}
		}
		
		return $specsWithNames;
	}
	
	static private function getNextItemWithMasterDepth($itemKey, $specsWithDepths) {
		foreach ($specsWithDepths as $key => $item) {
			if ($key > $itemKey && $item['depth'] == $specsWithDepths[$itemKey]['depth'] + 1) {
				return $item;
			}
		}
		
		return null;
	}
	
	static private function getPrevItemWithMasterDepth($itemKey, $specsWithDepths) {
		$masterItem = null;
		foreach ($specsWithDepths as $key => $item) {
			if ($key < $itemKey && $item['depth'] == $specsWithDepths[$itemKey]['depth'] - 1) {
				$masterItem = $item;
			}
		}
		
		return $masterItem;
	}
	
	static private function parseSpecTreeRow($row) {
		$depth = null;
		$row = str_replace('->', '', $row, $depth);
		$parts = explode('(', $row);
		
		$className = trim($parts[0]);
		
		if (isset($parts[1])) {
			$name = trim(str_replace(')', '', $parts[1]));
		} else {
			$name = '';
		}

		return array($depth, $className, $name);
	}
	
	static public function getDeclaredEntities() {
		$result = array();
		
		$declaredFunctions = get_defined_functions();
		foreach ($declaredFunctions['user'] as $name) {
			$result[] = array('name' => $name, 'type' => 'function');
		}
		
		foreach (get_declared_interfaces() as $name) {
			$result[] = array('name' => $name, 'type' => 'interface');
		}
		
		foreach (get_declared_classes() as $name) {
			$result[] = array('name' => $name, 'type' => 'class');
		}
		
		if (function_exists('get_declared_traits')) {
			foreach (get_declared_traits() as $name) {
				$result[] = array('name' => $name, 'type' => 'trait');
			}
		}
		
		return $result;
	}
	
	static public function getDirectoryFilesRecursively($paths) {
		$resultFiles = array();
		
		if (is_array($paths)) {
			foreach ($paths as $path) {
				$resultFiles = array_merge($resultFiles, static::getDirectoryFilesRecursively($path));
			}
		} else {
			foreach (scandir($paths) as $file) {
				if (mb_substr($file, 0, 1, 'us-ascii') === '.') {
					continue;
				}

				$file = $paths . '/' . $file;

				if (is_dir($file)) {
					$resultFiles = array_merge($resultFiles, static::getDirectoryFilesRecursively($file));
				} else {
					$resultFiles[] = $file;
				}
			}
		}
		
		return $resultFiles;
	}
}