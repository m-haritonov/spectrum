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
//		$this->backupStaticProperties('\spectrum\tests\testHelpers\agents\core\PluginStub');

		\spectrum\tests\Test::$temp = null;
	}

	protected function tearDown()
	{
//		$this->restoreStaticProperties('\spectrum\tests\testHelpers\agents\core\PluginStub');
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
	 * Example 1 (add child specs):
	 * Spec(name)
	 * ->Spec
	 * ->->Spec
	 * ->Spec
	 * 
	 * Example 2 (add parent specs):
	 * ->Spec
	 * ->->Spec
	 * ->Spec
	 * Spec(name)
	 *
	 * @return array
	 */
	final protected function createSpecsTree($treePattern)
	{
		$treePattern = trim($treePattern);
		
		if (preg_match('/^\-\>/is', $treePattern))
			$isReverse = true;
		else
			$isReverse = false;

		$specs = array();
		$prevLevel = 0;
		$rows = preg_split("/\r?\n/s", $treePattern);
		
		if ($isReverse)
			$rows = array_reverse($rows, true);
		
		foreach ($rows as $key => $row)
		{
			list($level, $className, $name) = $this->parseSpecTreeRow($row, $key);

			if (array_key_exists($name, $specs))
				throw new \Exception('Name "' . $name . '" is already use');

			$spec = new $className();
			$specs[$name] = $spec;
			$specsOnLevels[$level] = $spec;

			if ($level - $prevLevel > 1)
				throw new \Exception('Next level can\'t jump more that one');
	
			if ($level > 0)
			{
				if ($isReverse)
					$specsOnLevels[$level - 1]->bindParentSpec($spec);
				else
					$specsOnLevels[$level - 1]->bindChildSpec($spec);
			}

			$prevLevel = $level;
		}

		return $specs;
	}

	private function parseSpecTreeRow($row, $defaultName)
	{
		$level = null;
		$row = str_replace('->', '', $row, $level);

		$parts = explode('(', $row);
		$className = trim($parts[0]);
		
		if (isset($parts[1]))
			$name = $parts[1];
		else
			$name = '';

		$name = trim(str_replace(')', '', $name));

		if ($name == '')
			$name = $defaultName;

		return array($level, '\spectrum\core\\' . $className, $name);
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
		$this->assertSame(array($specs['aaa'], $specs['bbb'], $specs['ccc']), $specs[6]->getParentSpecs());
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

	final public function testCreateSpecsTree_ThrowsExceptionWhenLevelIsBreakMoreThenOne()
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