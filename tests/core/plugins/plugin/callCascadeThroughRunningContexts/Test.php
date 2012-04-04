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

namespace spectrum\core\plugins\plugin\callCascadeThroughRunningContexts;
require_once dirname(__FILE__) . '/../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Test extends \spectrum\core\plugins\plugin\Test
{
	public function testNoParents_ShouldBeCallProperMethodFromSelfPlugin()
	{
		$specs = $this->createSpecsTree($this->currentSpecClass . '(spec)');

		$this->executeContext(function() use($specs){
			$specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo');
		}, $specs['spec']);

		$this->assertSame(array(), \spectrum\Test::$tmp['getFoo']['arguments']);
	}

	public function testNoParents_ShouldBePassArgumentsToSelfPluginMethod()
	{
		$specs = $this->createSpecsTree($this->currentSpecClass . '(spec)');

		$this->executeContext(function() use($specs){
			$specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array('bar', 'baz'));
		}, $specs['spec']);

		$this->assertSame(array('bar', 'baz'), \spectrum\Test::$tmp['getFoo']['arguments']);
	}

	public function testNoParents_SelfReturnIsEmpty_ShouldBeReturnDefaultValue()
	{
		$specs = $this->createSpecsTree($this->currentSpecClass . '(spec)');
		$specs['spec']->testPlugin->setFoo(null);

		$this->executeContext(function() use($specs, &$result){
			$result = $specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array(), 'bar');
		}, $specs['spec']);

		$this->assertEquals('bar', $result);
	}

	public function testNoParents_SelfReturnIsNotEmpty_ShouldBeReturnSelfReturn()
	{
		$specs = $this->createSpecsTree($this->currentSpecClass . '(spec)');
		$specs['spec']->testPlugin->setFoo(true);

		$this->executeContext(function() use($specs, &$result){
			$result = $specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array(), 'bar');
		}, $specs['spec']);

		$this->assertEquals(true, $result);
	}

	public function testNoParents_SelfReturnIsLikeEmpty_ShouldBeReturnSelfReturn()
	{
		$specs = $this->createSpecsTree($this->currentSpecClass . '(spec)');
		$specs['spec']->testPlugin->setFoo('');

		$this->executeContext(function() use($specs, &$result){
			$result = $specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array(), 'bar');
		}, $specs['spec']);

		$this->assertEquals('', $result);
	}

	public function testNoParents_SelfReturnIsEmpty_EmptyReturnValueSetsToString_ShouldBeReturnDefaultValue()
	{
		$specs = $this->createSpecsTree($this->currentSpecClass . '(spec)');
		$specs['spec']->testPlugin->setFoo('notNull');

		$this->executeContext(function() use($specs, &$result){
			$result = $specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array(), 'bar', 'notNull');
		}, $specs['spec']);

		$this->assertEquals('bar', $result);
	}

/**/

	public function testHasParents_Describe_ShouldBeCallProperMethodFromParentPlugin()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(spec)
		');

		$this->executeContext(function() use($specs){
			$specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo');
		}, $specs['spec']);

		$this->assertSame(array(), \spectrum\Test::$tmp['getFoo']['arguments']);
	}

	public function testHasParents_Describe_ShouldBePassArgumentsToParentPluginMethod()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(spec)
		');

		$this->executeContext(function() use($specs){
			$specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array('bar', 'baz'));
		}, $specs['spec']);

		$this->assertSame(array('bar', 'baz'), \spectrum\Test::$tmp['getFoo']['arguments']);
	}

	public function testHasParents_Describe_ParentReturnIsEmpty_ShouldBeReturnDefaultValue()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo(null);

		$this->executeContext(function() use($specs, &$result){
			$result = $specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array(), 'bar');
		}, $specs['spec']);

		$this->assertEquals('bar', $result);
	}

	public function testHasParents_Describe_ParentReturnIsNotEmpty_ShouldBeReturnParentReturn()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo(true);

		$this->executeContext(function() use($specs, &$result){
			$result = $specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array(), 'bar');
		}, $specs['spec']);

		$this->assertEquals(true, $result);
	}

	public function testHasParents_Describe_ParentReturnIsLikeEmpty_ShouldBeReturnParentReturn()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('');

		$this->executeContext(function() use($specs, &$result){
			$result = $specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array(), 'bar');
		}, $specs['spec']);

		$this->assertEquals('', $result);
	}

	public function testHasParents_Describe_ParentReturnIsEmpty_EmptyReturnValueSetsToString_ShouldBeReturnDefaultValue()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('notNull');
		$specs['spec']->testPlugin->setFoo('notNull');

		$this->executeContext(function() use($specs, &$result){
			$result = $specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array(), 'bar', 'notNull');
		}, $specs['spec']);

		$this->assertEquals('bar', $result);
	}

/**/

	public function testHasParents_Context_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Context
			->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

	public function testHasParents_Context_OnlySelfReturnIsNotEmpty_ShouldBeReturnSelfReturn()
	{
		$specs = $this->createSpecsTree('
			Context
			->' . $this->currentSpecClass . '(spec)
		');

		$specs['spec']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

	public function testHasParents_Context_AllReturnIsNotEmpty_ShouldBeReturnSelfReturn()
	{
		$specs = $this->createSpecsTree('
			Context
			->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');
		$specs['spec']->testPlugin->setFoo('val2');

		$this->assertCallCascadeReturnSame('val2', $specs['spec']);
	}

/**/

	public function testHasParents_DescribeDescribe_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

	public function testHasParents_DescribeDescribe_OnlyLevel2ReturnIsNotEmpty_ShouldBeReturnLevel2Return()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[1]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

	public function testHasParents_DescribeDescribe_Level1AndLevel2ReturnIsNotEmpty_ShouldBeReturnLevel2Return()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');
		$specs[1]->testPlugin->setFoo('val2');

		$this->assertCallCascadeReturnSame('val2', $specs['spec']);
	}

	public function testHasParents_DescribeDescribe_AllReturnIsNotEmpty_ShouldBeReturnSelfReturn()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');
		$specs[1]->testPlugin->setFoo('val2');
		$specs['spec']->testPlugin->setFoo('val3');

		$this->assertCallCascadeReturnSame('val3', $specs['spec']);
	}

/**/

	public function testHasParents_ContextContext_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Context
			->Context
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

/**/

	public function testHasParents_DescribeContext_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

/**/

	public function testHasParents_ContextDescribe_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Context
			->Describe
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

/**/

	public function testHasParents_DescribeDescribeDescribe_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

/**/

	public function testHasParents_DescribeContextDescribe_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

/**/

	public function testHasParents_DescribeDescribeContext_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

/**/

	public function testHasParents_DescribeContextContext_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

/**/

	public function testHasParents_ContextContextContext_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Context
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

/**/

	public function testHasParents_ContextDescribeContext_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Context
			->Describe
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

/**/

	public function testHasParents_ContextContextDescribe_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Context
			->Context
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

/**/

	public function testHasParents_ContextDescribeDescribe_OnlyLevel1ReturnIsNotEmpty_ShouldBeReturnLevel1Return()
	{
		$specs = $this->createSpecsTree('
			Context
			->Describe
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame('val1', $specs['spec']);
	}

/*** Test ware ***/

	abstract protected function executeContext($callback, \spectrum\core\SpecInterface $spec);

	protected function assertCallCascadeReturnSame($expectedResult, \spectrum\core\SpecInterface $spec, $methodName = 'getFoo')
	{
		$this->executeContext(function() use($spec, $methodName, &$result){
			$result = $spec->testPlugin->callCascadeThroughRunningContexts($methodName);
		}, $spec);

		$this->assertSame($expectedResult, $result);
	}
}