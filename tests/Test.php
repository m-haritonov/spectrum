<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests;

use spectrum\config;

require_once __DIR__ . '/init.php';

abstract class Test extends \PHPUnit_Framework_TestCase
{
	public static $temp;
	private static $classNumber = 0;
	private $staticPropertiesBackups = array();

	protected function setUp()
	{
		parent::setUp();
		
		$this->backupStaticProperties('\spectrum\config');
		$this->backupStaticProperties('\spectrum\constructionCommands\callBroker');
		$this->backupStaticProperties('\spectrum\core\plugins\basePlugins\Output');
		$this->backupStaticProperties('\spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\SpecList');
		
		config::unregisterSpecPlugins('\spectrum\core\plugins\basePlugins\reports\Reports');
		\spectrum\tests\Test::$temp = null;
	}

	protected function tearDown()
	{
		$this->restoreStaticProperties('\spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\SpecList');
		$this->restoreStaticProperties('\spectrum\core\plugins\basePlugins\Output');
		$this->restoreStaticProperties('\spectrum\constructionCommands\callBroker');
		$this->restoreStaticProperties('\spectrum\config');

		parent::tearDown();
	}

	final protected function backupStaticProperties($className)
	{
		$reflection = new \ReflectionClass($className);
		$this->staticPropertiesBackups[$className] = $reflection->getStaticProperties();
	}

	final protected function restoreStaticProperties($className)
	{
		foreach ($this->staticPropertiesBackups[$className] as $name => $value)
		{
			$propertyReflection = new \ReflectionProperty($className, $name);
			$propertyReflection->setAccessible(true);
			$propertyReflection->setValue(null, $value);
		}
	}
	
	final protected function createClass($code)
	{
		$namespace = 'spectrum\tests\testware\_dynamicClasses_';
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
	
	final protected function createInterface($code)
	{
		$namespace = 'spectrum\tests\testware\_dynamicClasses_';
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
	
	final protected function registerPluginWithCodeInEvent($code, $eventName = 'onSpecRunStart')
	{
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "' . $eventName . '", "method" => "' . $eventName . '", "order" => 100),
					);
				}
				
				public function ' . $eventName . '()
				{
					' . $code . '
				}
			}
		');
		
		\spectrum\config::registerSpecPlugin($pluginClassName);
		return $pluginClassName;
	}
	
	final protected function assertThrowsException($expectedClass, $stringInMessageOrCallback, $callback = null)
	{
		if ($callback === null)
		{
			$message = null;
			$callback = $stringInMessageOrCallback;
		}
		else
			$message = $stringInMessageOrCallback;

		try {
			call_user_func($callback);
		}
		catch (\Exception $e)
		{
			$actualClass = '\\' . get_class($e);
			// Class found
			if ($actualClass == $expectedClass || is_subclass_of($actualClass, $expectedClass))
			{
				if ($message !== null)
					$this->assertContains($message, $e->getMessage());
				
				return;
			}
		}

		$this->fail('Exception "' . $expectedClass . '" not thrown');
	}
	
	final protected function getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand($name = null, $contexts = null, $body = null, $settings = null)
	{
		$values = array(
			'name' => array('some name text', 123),
			'contexts' => array(array(), array('aaa' => array('bbb', 'ccc')), function(){}, function(){
				\spectrum\constructionCommands\callBroker::group(null, null, function(){}, null);
				\spectrum\constructionCommands\callBroker::test(null, null, function(){}, null);
			}),
			'body' => array(function(){}, function(){
				\spectrum\constructionCommands\callBroker::group(null, null, function(){}, null);
				\spectrum\constructionCommands\callBroker::test(null, null, function(){}, null);
			}),
			'settings' => array(true, false, 8, 'koi8-r', array(), array('inputCharset' => 'koi8-r')),
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
		foreach ($values['name'] as $valueOfName)
		{
			foreach ($values['contexts'] as $valueOfContexts)
			{
				foreach ($values['body'] as $valueOfBody)
				{
					foreach ($values['settings'] as $valueOfSettings)
					{
						foreach ($patterns as $pattern)
						{
							if ($name !== null)
							{
								if (in_array('name', $pattern))
									$valueOfName = $name;
								else
									continue;
							}
							
							if ($contexts !== null)
							{
								if (in_array('contexts', $pattern))
									$valueOfContexts = $contexts;
								else
									continue;
							}
							
							if ($body !== null)
							{
								if (in_array('body', $pattern))
									$valueOfBody = $body;
								else
									continue;
							}
							
							if ($settings !== null)
							{
								if (in_array('settings', $pattern))
									$valueOfSettings = $settings;
								else
									continue;
							}
							
							$row = $pattern;
							
							$key = array_search('name', $pattern, true);
							if ($key !== false)
								$row[$key] = $valueOfName;
								
							$key = array_search('contexts', $pattern, true);
							if ($key !== false)
								$row[$key] = $valueOfContexts;
								
							$key = array_search('body', $pattern, true);
							if ($key !== false)
								$row[$key] = $valueOfBody;
								
							$key = array_search('settings', $pattern, true);
							if ($key !== false)
								$row[$key] = $valueOfSettings;
							
							$row = array($row);
							if (!in_array($row, $rows, true))
								$rows[] = $row;
						}
					}
				}
			}
		}
		
		return $rows;
	}
	
/**/

	/**
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
	 * @param $specBindings Example:
	 *                      array(
	 *                          'name' => array(4, 5, 6, 'aaa'),
	 *                          4 => array('bbb', 'ccc'),
	 *                          5 => array('zzz'),
	 *                      );
	 * 
	 * @return array
	 */
	final protected function createSpecsTree($specTreePattern, array $specBindings = array())
	{
		$specTreePattern = trim($specTreePattern);
		
		if (preg_match('/^\-\>/is', $specTreePattern))
			$isReverse = true;
		else
			$isReverse = false;

		$specsWithNames = array();
		$specsWithDepths = array();
		
		foreach (preg_split("/\r?\n/s", $specTreePattern) as $key => $row)
		{
			list($depth, $className, $name) = $this->parseSpecTreeRow($row);
			
			if ($depth == 0)
				$isReverse = false;
			else if ($isReverse)
				$depth = -$depth;
			
			$className = '\spectrum\core\\' . $className;
			
			if ($name == '')
				$name = $key;
			
			if (array_key_exists($name, $specsWithNames))
				throw new \Exception('Name "' . $name . '" is already used');
			
			$spec = new $className();
			$specsWithNames[$name] = $spec;
			$specsWithDepths[] = array('spec' => $spec, 'depth' => $depth);
		}

		foreach ($specsWithDepths as $key => $item)
		{
			if ($item['depth'] < 0)
			{
				$masterItem = $this->getNextItemWithMasterDepth($key, $specsWithDepths);
				
				if (!$masterItem)
					throw new \Exception('Depth can not jump more than one');
				
				$masterItem['spec']->bindParentSpec($item['spec']);
			}
			else if ($item['depth'] > 0)
			{
				$masterItem = $this->getPrevItemWithMasterDepth($key, $specsWithDepths);
				
				if (!$masterItem)
					throw new \Exception('Depth can not jump more than one');
				
				$masterItem['spec']->bindChildSpec($item['spec']);
			}
		}
		
		foreach ($specBindings as $parentSpecName => $childrenSpecNames)
		{
			foreach ((array) $childrenSpecNames as $childSpecName)
				$specsWithNames[$parentSpecName]->bindChildSpec($specsWithNames[$childSpecName]);
		}
		
		return $specsWithNames;
	}
	
	private function getNextItemWithMasterDepth($itemKey, $specsWithDepths)
	{
		foreach ($specsWithDepths as $key => $item)
		{
			if ($key > $itemKey && $item['depth'] == $specsWithDepths[$itemKey]['depth'] + 1)
				return $item;
		}
		
		return null;
	}
	
	private function getPrevItemWithMasterDepth($itemKey, $specsWithDepths)
	{
		$masterItem = null;
		foreach ($specsWithDepths as $key => $item)
		{
			if ($key < $itemKey && $item['depth'] == $specsWithDepths[$itemKey]['depth'] - 1)
				$masterItem = $item;
		}
		
		return $masterItem;
	}
	
	private function parseSpecTreeRow($row)
	{
		$depth = null;
		$row = str_replace('->', '', $row, $depth);
		$parts = explode('(', $row);
		
		$className = trim($parts[0]);
		
		if (isset($parts[1]))
			$name = trim(str_replace(')', '', $parts[1]));
		else
			$name = '';

		return array($depth, $className, $name);
	}
	
	final public function testCreateSpecsTree_ReverseOrder_AddsUpSpecsToBottomSpecsAsParents()
	{
		$specs = $this->createSpecsTree('
			->->Spec
			->Spec(ccc)
			->->->Spec
			->->Spec
			->Spec(bbb)
			->Spec(aaa)
			Spec
		');

		$this->assertSame(7, count($specs));
		$this->assertSame(array($specs['ccc'], $specs['bbb'], $specs['aaa']), $specs[6]->getParentSpecs());
		$this->assertSame(array(), $specs[6]->getChildSpecs());
		
		$this->assertSame(array(), $specs['aaa']->getParentSpecs());
		$this->assertSame(array($specs[6]), $specs['aaa']->getChildSpecs());
		
		$this->assertSame(array($specs[3]), $specs['bbb']->getParentSpecs());
		$this->assertSame(array($specs[6]), $specs['bbb']->getChildSpecs());
		
		$this->assertSame(array($specs[2]), $specs[3]->getParentSpecs());
		$this->assertSame(array($specs['bbb']), $specs[3]->getChildSpecs());
		
		$this->assertSame(array(), $specs[2]->getParentSpecs());
		$this->assertSame(array($specs[3]), $specs[2]->getChildSpecs());
		
		$this->assertSame(array($specs[0]), $specs['ccc']->getParentSpecs());
		$this->assertSame(array($specs[6]), $specs['ccc']->getChildSpecs());
		
		$this->assertSame(array(), $specs[0]->getParentSpecs());
		$this->assertSame(array($specs['ccc']), $specs[0]->getChildSpecs());
	}
	
	final public function testCreateSpecsTree_ReverseOrder_ThrowsExceptionWhenDepthIsBreakMoreThenOne()
	{
		try
		{
			$this->createSpecsTree('
				->->Spec
				Spec
			');
		}
		catch (\Exception $e)
		{
			return;
		}

		$this->fail('Should be thrown exception');
	}
	
	final public function testCreateSpecsTree_DirectOrder_AddsBottomSpecsToUpSpecsAsChildren()
	{
		$specs = $this->createSpecsTree('
			Spec
			->Spec(aaa)
			->Spec(bbb)
			->->Spec
			->->->Spec
			->Spec(ccc)
			->->Spec
		');

		$this->assertSame(7, count($specs));
		$this->assertSame(array(), $specs[0]->getParentSpecs());
		$this->assertSame(array($specs[0]), $specs['aaa']->getParentSpecs());
		$this->assertSame(array($specs[0]), $specs['bbb']->getParentSpecs());
		$this->assertSame(array($specs['bbb']), $specs[3]->getParentSpecs());
		$this->assertSame(array($specs[3]), $specs[4]->getParentSpecs());
		$this->assertSame(array($specs[0]), $specs['ccc']->getParentSpecs());
		$this->assertSame(array($specs['ccc']), $specs[6]->getParentSpecs());
	}
	
	final public function testCreateSpecsTree_DirectOrder_ThrowsExceptionWhenDepthIsBreakMoreThenOne()
	{
		try
		{
			$this->createSpecsTree('
				Spec
				->->Spec
			');
		}
		catch (\Exception $e)
		{
			return;
		}

		$this->fail('Should be thrown exception');
	}

	final public function testCreateSpecsTree_MixedOrder_AddsUpSpecsToBottomSpecsAsParentsAndAddsBottomSpecsToUpSpecsAsChildren()
	{
		$specs = $this->createSpecsTree('
			->->Spec
			->Spec
			->->->Spec
			->->Spec
			->Spec
			->Spec
			Spec
			->Spec
			->Spec
			->->Spec
			->->->Spec
			->Spec
			->->Spec
		');
		
		$this->assertSame(13, count($specs));
		
		$this->assertSame(array(), $specs[0]->getParentSpecs());
		$this->assertSame(array($specs[1]), $specs[0]->getChildSpecs());
		
		$this->assertSame(array($specs[0]), $specs[1]->getParentSpecs());
		$this->assertSame(array($specs[6]), $specs[1]->getChildSpecs());
		
		$this->assertSame(array(), $specs[2]->getParentSpecs());
		$this->assertSame(array($specs[3]), $specs[2]->getChildSpecs());
		
		$this->assertSame(array($specs[2]), $specs[3]->getParentSpecs());
		$this->assertSame(array($specs[4]), $specs[3]->getChildSpecs());
		
		$this->assertSame(array($specs[3]), $specs[4]->getParentSpecs());
		$this->assertSame(array($specs[6]), $specs[4]->getChildSpecs());
		
		$this->assertSame(array(), $specs[5]->getParentSpecs());
		$this->assertSame(array($specs[6]), $specs[5]->getChildSpecs());
		
		$this->assertSame(array($specs[1], $specs[4], $specs[5]), $specs[6]->getParentSpecs());
		$this->assertSame(array($specs[7], $specs[8], $specs[11]), $specs[6]->getChildSpecs());
		
		$this->assertSame(array($specs[6]), $specs[7]->getParentSpecs());
		$this->assertSame(array(), $specs[7]->getChildSpecs());
		
		$this->assertSame(array($specs[6]), $specs[8]->getParentSpecs());
		$this->assertSame(array($specs[9]), $specs[8]->getChildSpecs());
		
		$this->assertSame(array($specs[8]), $specs[9]->getParentSpecs());
		$this->assertSame(array($specs[10]), $specs[9]->getChildSpecs());
		
		$this->assertSame(array($specs[9]), $specs[10]->getParentSpecs());
		$this->assertSame(array(), $specs[10]->getChildSpecs());
		
		$this->assertSame(array($specs[6]), $specs[11]->getParentSpecs());
		$this->assertSame(array($specs[12]), $specs[11]->getChildSpecs());
		
		$this->assertSame(array($specs[11]), $specs[12]->getParentSpecs());
		$this->assertSame(array(), $specs[12]->getChildSpecs());
	}

	final public function testCreateSpecsTree_ThrowsExceptionWhenNameIsDuplicate()
	{
		try
		{
			$this->createSpecsTree('
				Spec(aaa)
				->Spec(aaa)
			');
		}
		catch (\Exception $e)
		{
			return;
		}

		$this->fail('Should be thrown exception');
	}
}