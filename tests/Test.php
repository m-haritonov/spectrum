<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace spectrum;

require_once dirname(__FILE__) . '/init.php';

abstract class Test extends \PHPUnit_Framework_TestCase
{
	public static $tmp;
	private $staticPropertiesBackups = array();

	protected function setUp()
	{
		parent::setUp();

		$this->backupStaticProperties('\spectrum\core\Config');
		$this->backupStaticProperties('\spectrum\core\Registry');
		$this->backupStaticProperties('\spectrum\core\plugins\Manager');
		$this->backupStaticProperties('\spectrum\constructionCommands\Config');
		$this->backupStaticProperties('\spectrum\constructionCommands\Manager');
		$this->backupStaticProperties('\spectrum\reports\Config');

		\spectrum\Test::$tmp = null;
		\spectrum\core\testEnv\PluginStub::reset();
	}

	protected function tearDown()
	{
		$this->restoreStaticProperties('\spectrum\reports\Config');
		$this->restoreStaticProperties('\spectrum\constructionCommands\Manager');
		$this->restoreStaticProperties('\spectrum\constructionCommands\Config');
		$this->restoreStaticProperties('\spectrum\core\plugins\Manager');
		$this->restoreStaticProperties('\spectrum\core\Registry');
		$this->restoreStaticProperties('\spectrum\core\Config');

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

/**/

	final public function testCreateSpecsTree_ShouldBeReturnCreatedSpecsWithNamesOrIndexes()
	{
		$this->restoreStaticProperties('\spectrum\core\plugins\Manager');
		$specs = $this->createSpecsTree('
			Describe
			->Context(foo)
			->->It
			->It(bar)
		');

		$this->assertEquals(4, count($specs));
		$this->assertTrue($specs['0'] instanceof \spectrum\core\SpecContainerDescribeInterface);
		$this->assertTrue($specs['foo'] instanceof \spectrum\core\SpecContainerContextInterface);
		$this->assertTrue($specs['2'] instanceof \spectrum\core\SpecItemIt);
		$this->assertTrue($specs['bar'] instanceof \spectrum\core\SpecItemIt);
		$this->assertNotSame($specs['2'], $specs['bar']);
	}

	final public function testCreateSpecsTree_ShouldBeReturnCreatedSpecsWithNamesAndIndexesIfAddIndexNameAlwaysIsTrue()
	{
		$this->restoreStaticProperties('\spectrum\core\plugins\Manager');
		$specs = $this->createSpecsTree('
			Describe(foo)
			->It(bar)
		', array(), true);

		$this->assertEquals(4, count($specs));

		$this->assertTrue($specs['0'] instanceof \spectrum\core\SpecContainerDescribe);
		$this->assertTrue($specs['foo'] instanceof \spectrum\core\SpecContainerDescribe);
		$this->assertSame($specs['0'], $specs['foo']);

		$this->assertTrue($specs['1'] instanceof \spectrum\core\SpecItemIt);
		$this->assertTrue($specs['bar'] instanceof \spectrum\core\SpecItemIt);
		$this->assertSame($specs['1'], $specs['bar']);

		$this->assertNotSame($specs['0'], $specs['1']);
	}

	final public function testCreateSpecsTree_ShouldBeReturnPreparedInstanceIfExists()
	{
		$this->restoreStaticProperties('\spectrum\core\plugins\Manager');
		$describe = new \spectrum\core\SpecContainerDescribe();
		$it = new \spectrum\core\SpecItemIt();

		$specs = $this->createSpecsTree('
			Describe
			->It(foo)
			->It
			->It(bar)
		', array(
			'0' => $describe,
			'foo' => $it,
		));

		$this->assertEquals(4, count($specs));

		$this->assertSame($specs['0'], $describe);
		$this->assertSame($specs['foo'], $it);

		$this->assertNotSame($specs['2'], $describe);
		$this->assertNotSame($specs['2'], $it);

		$this->assertNotSame($specs['bar'], $describe);
		$this->assertNotSame($specs['bar'], $it);
	}

	final public function testCreateSpecsTree_ShouldBeThrowExceptionIfHasNotUsefulPreparedInstances()
	{
		try
		{
			$this->createSpecsTree('
				Describe
				It
			', array(
				0 => new \spectrum\core\SpecContainerDescribe(),
				1 => new \spectrum\core\SpecContainerDescribe(),
				2 => new \spectrum\core\SpecContainerDescribe(),
			));
		}
		catch (Exception $e)
		{
			return;
		}

		$this->fail('Should be thrown exception');
	}

	final public function testCreateSpecsTree_ShouldBeThrowExceptionIfPreparedInstanceNotInstanceOfDeclaredClass()
	{
		try
		{
			$this->createSpecsTree('
				Describe
			', array(
				0 => new \spectrum\core\SpecItemIt(),
			));
		}
		catch (Exception $e)
		{
			return;
		}

		$this->fail('Should be thrown exception');
	}

	final public function testCreateSpecsTree_ShouldBeThrowExceptionWhenDuplicateNames()
	{
		try
		{
			$this->createSpecsTree('
				Describe(foo)
				->It(foo)
			');
		}
		catch (Exception $e)
		{
			return;
		}

		$this->fail('Should be thrown exception');
	}

	final public function testCreateSpecsTree_ShouldBeAddChildSpecsToParent()
	{
		$this->restoreStaticProperties('\spectrum\core\plugins\Manager');
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Describe
			->->->It
			->It
			Describe
		');

		$this->assertNull($specs['0']->getParent());
		$this->assertSame($specs['0'], $specs['1']->getParent());
		$this->assertSame($specs['1'], $specs['2']->getParent());
		$this->assertSame($specs['2'], $specs['3']->getParent());
		$this->assertSame($specs['0'], $specs['4']->getParent());
		$this->assertNull($specs['5']->getParent());
	}

	final public function testCreateSpecsTree_ShouldBeThrowExceptionIfLevelBreakMoreThenOne()
	{
		try
		{
			$this->createSpecsTree('
				Describe
				->->It
			');
		}
		catch (Exception $e)
		{
			return;
		}

		$this->fail('Should be thrown exception');
	}

	/**
	 * $treePattern example:
	 * Describe(root_spec)
	 * ->Context(name)
	 * ->->It
	 * ->Context
	 * Describe
	 *
	 * @return array
	 */
	protected function createSpecsTree($treePattern, array $preparedInstances = array(), $addIndexNameAlways = false)
	{
		$treePattern = trim($treePattern);

		$specs = array();
		$prevLevel = 0;
		foreach (preg_split("/\r?\n/s", $treePattern) as $key => $row)
		{
			list($level, $shortClass, $name) = $this->parseSpecTreeRow($row, $key);

			if (array_key_exists($name, $specs))
				throw new Exception('Name "' . $name . '" already exists');

			$spec = $this->createSpecOrGetExists($name, $preparedInstances, $shortClass);
			$specs[$name] = $spec;

			if ($addIndexNameAlways && $name != (string) $key)
			{
				if (array_key_exists($key, $specs))
					throw new Exception('Name "' . $key . '" already exists');

				$specs[$key] = $spec;
			}

			$specsOnLevels[$level] = $spec;

			$parentSpec = $this->getParentSpec($specsOnLevels, $level, $prevLevel);
			if ($parentSpec)
				$parentSpec->addSpec($spec);

			$prevLevel = $level;
		}

		$diff = array_diff_key($preparedInstances, $specs);
		if ($diff)
			throw new Exception('PreparedInstances has not useful instances: ' . print_r(array_keys($diff)));

		return $specs;
	}

	private function parseSpecTreeRow($row, $defaultName)
	{
		$level = null;
		$row = str_replace('->', '', $row, $level);

		$parts = explode('(', $row);
		$shortClass = $parts[0];
		if (isset($parts[1]))
			$name = $parts[1];
		else
			$name = '';

		$shortClass = trim($shortClass);

		$name = str_replace(')', '', $name);
		$name = trim($name);

		if ($name == '')
			$name = $defaultName;

		return array($level, $shortClass, $name);
	}

	private function createSpecOrGetExists($name, $preparedInstances, $shortClass)
	{
		$newSpecClass = $this->getFullClassName($shortClass);

		if (array_key_exists($name, $preparedInstances))
		{
			if (!is_a($preparedInstances[$name], $newSpecClass))
				throw new Exception('PreparedInstances should be instance of declared class');

			$instance = $preparedInstances[$name];
		}
		else
			$instance = new $newSpecClass();

		$instance->setName($name);
		return $instance;
	}

	private function getFullClassName($shortClassName)
	{
		$shortClassName = preg_replace('/^\\\\spectrum\\\\core\\\\testEnv\\\\/s', '', $shortClassName);
		$shortClassName = preg_replace('/^\\\\spectrum\\\\core\\\\/s', '', $shortClassName);
		$shortClassName = preg_replace('/^SpecContainer/s', '', $shortClassName);
		$shortClassName = preg_replace('/^SpecItem/s', '', $shortClassName);
		
		$shortToFull = array(
			'ArgumentsProvider' => '\spectrum\core\SpecContainerArgumentsProvider',
			'Pattern' => '\spectrum\core\SpecContainerPattern',
			'Describe' => '\spectrum\core\SpecContainerDescribe',
			'Context' => '\spectrum\core\SpecContainerContext',
			'It' => '\spectrum\core\SpecItemIt',

			'ArgumentsProviderMock' => '\spectrum\core\testEnv\SpecContainerArgumentsProviderMock',
			'PatternMock' => '\spectrum\core\testEnv\SpecContainerPatternMock',
			'DescribeMock' => '\spectrum\core\testEnv\SpecContainerDescribeMock',
			'ContextMock' => '\spectrum\core\testEnv\SpecContainerContextMock',
			'ItMock' => '\spectrum\core\testEnv\SpecItemItMock',
		);

		if ($shortToFull[$shortClassName])
			return $shortToFull[$shortClassName];
		else
			throw new Exception('Undefined spec class');
	}

	private function getParentSpec($specsOnLevels, $level, $prevLevel)
	{
		if ($level - $prevLevel > 1)
			throw new Exception('Next level can\'t jump more that one');

		if ($level > 0)
			return $specsOnLevels[$level - 1];
		else
			return null;
	}

	public function injectToRunStartCallsCounter(\spectrum\core\SpecInterface $spec, $counterName = 'callsCounter')
	{
		$spec->__injectFunctionToRunStart(function() use($counterName) {
			\spectrum\Test::$tmp[$counterName] = (int) \spectrum\Test::$tmp[$counterName] + 1;
		});
	}

	public function injectToRunStartSaveInstanceToCollection(\spectrum\core\SpecInterface $spec)
	{
		$spec->__injectFunctionToRunStart(function() use($spec) {
			\spectrum\Test::$tmp['instancesCollection'][] = $spec;
		});
	}

	public function injectToRunStartCallsOrderChecker(\spectrum\core\SpecInterface $spec, $expectedZeroBasedIndex)
	{
		$spec->__injectFunctionToRunStart(function() use($spec, $expectedZeroBasedIndex) {
			\spectrum\Test::$tmp['callsOrderChecker'][] = $expectedZeroBasedIndex;
		});
	}

	public function assertCallsCounterEquals($expectedCount, $counterName = 'callsCounter')
	{
		$this->assertEquals($expectedCount, (int) @\spectrum\Test::$tmp[$counterName]);
	}

	public function assertCallsInOrder($expectedCount)
	{
		$this->assertEquals($expectedCount, count((array) @\spectrum\Test::$tmp['callsOrderChecker']));

		foreach ((array) \spectrum\Test::$tmp['callsOrderChecker'] as $actualIndex => $expectedIndex)
		{
			$this->assertEquals($expectedIndex, $actualIndex);
		}
	}

	public function assertInstanceInCollection(\spectrum\core\SpecInterface $spec)
	{
		$this->assertTrue(in_array($spec, (array) \spectrum\Test::$tmp['instancesCollection'], true));
	}

	public function assertInstanceNotInCollection(\spectrum\core\SpecInterface $spec)
	{
		$this->assertFalse(in_array($spec, (array) \spectrum\Test::$tmp['instancesCollection'], true));
	}

	public function assertThrowException($expectedClass, $stringInMessageOrCallback, $callback = null)
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
}