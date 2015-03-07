<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic;

use spectrum\config;
use spectrum\core\Spec;
use spectrum\core\SpecInterface;

require_once __DIR__ . '/../init.php';

abstract class Test extends \PHPUnit_Framework_TestCase {
	static public $temp;
	static private $classNumber = 0;
	
	private $classStaticPropertyBackups = array();
	private $objectPropertyBackups = array();

	protected function setUp() {
		parent::setUp();
		
		\spectrum\_private\setCurrentBuildingSpec(null);
		
		$this->backupObjectProperties(\spectrum\_private\getRootSpec());
		$this->backupClassStaticProperties('\spectrum\config');
		$this->backupClassStaticProperties('\spectrum\_private\reports\html\components\specList');
		$this->backupClassStaticProperties('\spectrum\_private\reports\html\components\code\variable');
		$this->backupClassStaticProperties('\spectrum\_private\reports\text\components\specList');
		$this->backupClassStaticProperties('\spectrum\_private\reports\text\components\code\variable');
		
		\spectrum\tests\automatic\Test::$temp = null;
	}
	
	protected function tearDown() {
		$this->restoreClassStaticProperties('\spectrum\_private\reports\text\components\code\variable');
		$this->restoreClassStaticProperties('\spectrum\_private\reports\text\components\specList');
		$this->restoreClassStaticProperties('\spectrum\_private\reports\html\components\code\variable');
		$this->restoreClassStaticProperties('\spectrum\_private\reports\html\components\specList');
		$this->restoreClassStaticProperties('\spectrum\config');
		$this->restoreObjectProperties(\spectrum\_private\getRootSpec());

		$this->classStaticPropertyBackups = array();
		$this->objectPropertyBackups = array();
		
		parent::tearDown();
	}

	final protected function backupClassStaticProperties($className) {
		$reflection = new \ReflectionClass($className);
		$this->classStaticPropertyBackups[$className] = $reflection->getStaticProperties();
	}

	final protected function restoreClassStaticProperties($className) {
		foreach ($this->classStaticPropertyBackups[$className] as $name => $value) {
			$propertyReflection = new \ReflectionProperty($className, $name);
			$propertyReflection->setAccessible(true);
			$propertyReflection->setValue(null, $value);
		}
	}
	
	final protected function backupObjectProperties($object) {
		var_dump(serialize($object));
		$this->objectPropertyBackups[spl_object_hash($object)] = serialize($object);
	}

	final protected function restoreObjectProperties($object) {
		$objectReflection = new \ReflectionClass($object);
		
		$backupObject = unserialize($this->objectPropertyBackups[spl_object_hash($object)]);
		$backupObjectReflection = new \ReflectionClass($backupObject);
		
		foreach ($objectReflection->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE) as $objectProperty) {
			$objectProperty->setAccessible(true);
			
			$backupObjectProperty = $backupObjectReflection->getProperty($objectProperty->getName());
			$backupObjectProperty->setAccessible(true);
			
			$objectProperty->setValue($object, $backupObjectProperty->getValue($backupObject));
		}
	}
	
	final protected function getUniqueArrayElements(array $array, $preserveKeys = true) {
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
	
	final protected function getLastErrorHandler() {
		$lastErrorHandler = set_error_handler(function($errorSeverity, $errorMessage){});
		restore_error_handler();
		return $lastErrorHandler;
	}

	/**
	 * @return string Class name string in "US-ASCII" charset
	 */
	final protected function createClass($code) {
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
	
	final protected function createInterface($code) {
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

	/**
	 * @param null|\Closure $callback
	 */
	final protected function assertThrowsException($expectedClass, $stringInMessageOrCallback, $callback = null) {
		if ($callback === null) {
			$message = null;
			$callback = $stringInMessageOrCallback;
		} else {
			$message = $stringInMessageOrCallback;
		}

		try {
			$callback();
		} catch (\Exception $e) {
			$actualClass = '\\' . get_class($e);
			// Class found
			if ((string) $actualClass === (string) $expectedClass || is_subclass_of($actualClass, $expectedClass)) {
				if ($message !== null) {
					$this->assertSame($message, $e->getMessage());
				}
				
				return null;
			}
			
			throw $e;
		}

		$this->fail('Exception "' . $expectedClass . '" not thrown');
	}
	
	final protected function getProviderWithCorrectArgumentsForGroupAndTestBuilders($name = null, $contexts = null, $body = null, $settings = null) {
		$values = array(
			'name' => array('some name text', 123),
			'contexts' => array(array(), array('aaa' => array('bbb', 'ccc')), function(){}, function(){
				\spectrum\builders\group(null, null, function(){}, null);
				\spectrum\builders\test(null, null, function(){}, null);
			}),
			'body' => array(function(){}, function(){
				\spectrum\builders\group(null, null, function(){}, null);
				\spectrum\builders\test(null, null, function(){}, null);
			}),
			'settings' => array(true, false, 8, array(), array('breakOnFirstPhpError' => false), array(
				'catchPhpErrors' => 8,
				'breakOnFirstPhpError' => true,
				'breakOnFirstMatcherFail' => true,
			)),
		);
		
		$patterns = array(
			array('body'),
			array('body', 'settings'),
			
			array('contexts', 'body'),
			array('contexts', 'body', 'settings'),
			
			array('name', 'body'),
			array('name', 'body', 'settings'),
			
			array('name', 'contexts', 'body'),
			array('name', 'contexts', 'body', 'settings'),
			
			array(null, 'body', null),
			array(null, 'body', 'settings'),
			
			array(null, null, 'body', null),
			array(null, null, 'body', 'settings'),
			array(null, 'contexts', 'body', null),
			array(null, 'contexts', 'body', 'settings'),
			array('name', null, 'body', null),
			array('name', null, 'body', 'settings'),
			array('name', 'contexts', 'body', null),
		);
		
		$rows = array();
		foreach ($values['name'] as $valueOfName) {
			foreach ($values['contexts'] as $valueOfContexts) {
				foreach ($values['body'] as $valueOfBody) {
					foreach ($values['settings'] as $valueOfSettings) {
						foreach ($patterns as $pattern) {
							if ($name !== null) {
								if (in_array('name', $pattern)) {
									$valueOfName = $name;
								} else {
									continue;
								}
							}
							
							if ($contexts !== null) {
								if (in_array('contexts', $pattern)) {
									$valueOfContexts = $contexts;
								} else {
									continue;
								}
							}
							
							if ($body !== null) {
								if (in_array('body', $pattern)) {
									$valueOfBody = $body;
								} else {
									continue;
								}
							}
							
							if ($settings !== null) {
								if (in_array('settings', $pattern)) {
									$valueOfSettings = $settings;
								} else {
									continue;
								}
							}
							
							$row = $pattern;
							
							$key = array_search('name', $pattern, true);
							if ($key !== false) {
								$row[$key] = $valueOfName;
							}
								
							$key = array_search('contexts', $pattern, true);
							if ($key !== false) {
								$row[$key] = $valueOfContexts;
							}
								
							$key = array_search('body', $pattern, true);
							if ($key !== false) {
								$row[$key] = $valueOfBody;
							}
								
							$key = array_search('settings', $pattern, true);
							if ($key !== false) {
								$row[$key] = $valueOfSettings;
							}
							
							$row = array($row);
							if (!in_array($row, $rows, true)) {
								$rows[] = $row;
							}
						}
					}
				}
			}
		}
		
		return $rows;
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
	final protected function createSpecsByVisualPattern($pattern, array $additionalRelations = array(), $specClass = '\spectrum\core\Spec') {
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
	final protected function createSpecsByListPattern($pattern, array $additionalRelations = array()) {
		$pattern = trim($pattern);
		
		if (preg_match('/^\-\>/is', $pattern)) {
			$isReverse = true;
		} else {
			$isReverse = false;
		}

		$specsWithNames = array();
		$specsWithDepths = array();
		
		foreach (preg_split("/\r?\n/s", $pattern) as $key => $row) {
			list($depth, $className, $name) = $this->parseSpecTreeRow($row);
			
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
				$masterItem = $this->getNextItemWithMasterDepth($key, $specsWithDepths);
				
				if (!$masterItem) {
					throw new \Exception('Depth can not jump more than one');
				}
				
				$masterItem['spec']->bindParentSpec($item['spec']);
			} else if ($item['depth'] > 0) {
				$masterItem = $this->getPrevItemWithMasterDepth($key, $specsWithDepths);
				
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
	
	private function getNextItemWithMasterDepth($itemKey, $specsWithDepths) {
		foreach ($specsWithDepths as $key => $item) {
			if ($key > $itemKey && $item['depth'] == $specsWithDepths[$itemKey]['depth'] + 1) {
				return $item;
			}
		}
		
		return null;
	}
	
	private function getPrevItemWithMasterDepth($itemKey, $specsWithDepths) {
		$masterItem = null;
		foreach ($specsWithDepths as $key => $item) {
			if ($key < $itemKey && $item['depth'] == $specsWithDepths[$itemKey]['depth'] - 1) {
				$masterItem = $item;
			}
		}
		
		return $masterItem;
	}
	
	private function parseSpecTreeRow($row) {
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
}