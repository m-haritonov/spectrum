<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests;

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
		$this->backupStaticProperties('\spectrum\core\plugins\basePlugins\Output');
		$this->backupStaticProperties('\spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\SpecList');

		\spectrum\tests\Test::$temp = null;
	}

	protected function tearDown()
	{
		$this->restoreStaticProperties('\spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\SpecList');
		$this->restoreStaticProperties('\spectrum\core\plugins\basePlugins\Output');
		$this->restoreStaticProperties('\spectrum\config');

		parent::tearDown();
	}

	protected function backupStaticProperties($className)
	{
		$reflection = new \ReflectionClass($className);
		$this->staticPropertiesBackups[$className] = $reflection->getStaticProperties();
	}

	protected function restoreStaticProperties($className)
	{
		foreach ($this->staticPropertiesBackups[$className] as $name => $value)
		{
			$propertyReflection = new \ReflectionProperty($className, $name);
			$propertyReflection->setAccessible(true);
			$propertyReflection->setValue(null, $value);
		}
	}
	
	protected function createClass($classCode)
	{
		$namespace = 'spectrum\tests\testHelpers\_dynamicClasses_';
		$className = 'DynamicClass' . self::$classNumber;
		self::$classNumber++;
		
		$classCode = preg_replace(
			'/^(\s*abstract|\s*final)*\s*class\s*\.\.\./is',
			'namespace ' . $namespace . '; class ' . $className . ' ',
			$classCode
		);
		
		eval($classCode);
		return '\\' . $namespace . '\\' . $className;
	}
	
	public function assertThrowsException($expectedClass, $stringInMessageOrCallback, $callback = null)
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
	 * @return array
	 */
	final protected function createSpecsTree($specTreePattern)
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

		$this->assertEquals(7, count($specs));
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

		$this->assertEquals(7, count($specs));
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
		
		$this->assertEquals(13, count($specs));
		
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
	
/*
	protected function assertEventTriggeredCount($expectedCount, $eventName)
	{
		$eventClassName = $this->getEventClassNameByEventName($eventName);

		$count = 0;
		foreach (\spectrum\tests\Test::$temp['triggeredEvents'][$eventClassName] as $event)
		{
			if ($event['name'] == $eventName)
				$count++;
		}

		$this->assertEquals($expectedCount, $count);
	}

	protected function getEventClassNameByEventName($eventName)
	{
		return preg_replace('/(Before|After)$/s', '', $eventName);
	}
	
	public function injectToRunStartCallsCounter(\spectrum\core\SpecInterface $spec, $counterName = 'callsCounter')
	{
		$spec->__injectFunctionToRunStart(function() use($counterName) {
			\spectrum\tests\Test::$temp[$counterName] = (int) \spectrum\tests\Test::$temp[$counterName] + 1;
		});
	}

	public function injectToRunStartSaveInstanceToCollection(\spectrum\core\SpecInterface $spec)
	{
		$spec->__injectFunctionToRunStart(function() use($spec) {
			\spectrum\tests\Test::$temp['instancesCollection'][] = $spec;
		});
	}

	public function injectToRunStartCallsOrderChecker(\spectrum\core\SpecInterface $spec, $expectedZeroBasedIndex)
	{
		$spec->__injectFunctionToRunStart(function() use($spec, $expectedZeroBasedIndex) {
			\spectrum\tests\Test::$temp['callsOrderChecker'][] = $expectedZeroBasedIndex;
		});
	}

	public function assertCallsCounterEquals($expectedCount, $counterName = 'callsCounter')
	{
		$this->assertEquals($expectedCount, (int) @\spectrum\tests\Test::$temp[$counterName]);
	}

	public function assertCallsInOrder($expectedCount)
	{
		$this->assertEquals($expectedCount, count((array) @\spectrum\tests\Test::$temp['callsOrderChecker']));

		foreach ((array) \spectrum\tests\Test::$temp['callsOrderChecker'] as $actualIndex => $expectedIndex)
		{
			$this->assertEquals($expectedIndex, $actualIndex);
		}
	}

	public function assertInstanceInCollection(\spectrum\core\SpecInterface $spec)
	{
		$this->assertTrue(in_array($spec, (array) \spectrum\tests\Test::$temp['instancesCollection'], true));
	}

	public function assertInstanceNotInCollection(\spectrum\core\SpecInterface $spec)
	{
		$this->assertFalse(in_array($spec, (array) \spectrum\tests\Test::$temp['instancesCollection'], true));
	}
	
*/
}