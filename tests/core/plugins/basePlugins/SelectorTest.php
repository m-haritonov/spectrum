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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins;
use net\mkharitonov\spectrum\core\SpecItemIt;

require_once dirname(__FILE__) . '/../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class SelectorTest extends Test
{
	public function testGetRoot_ShouldBeReturnRootAncestor()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->It(it)
		');

		$this->assertSame($specs[0], $specs['it']->selector->getRoot());
	}

	public function testGetRoot_ShouldBeReturnParentIfNoOtherAncestors()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It(it)
		');

		$this->assertSame($specs[0], $specs['it']->selector->getRoot());
	}

	public function testGetRoot_ShouldBeReturnSelfIfNoParent()
	{
		$it = new \net\mkharitonov\spectrum\core\SpecItemIt();
		$this->assertSame($it, $it->selector->getRoot());
	}

/**/

	public function testGetNearestNotContextAncestor_ShouldBeFindOnlyNotContext()
	{
		$spec = new \net\mkharitonov\spectrum\core\SpecContainerContext();
		$parent1 = new \net\mkharitonov\spectrum\core\SpecContainerContext();
		$parent2 = new \net\mkharitonov\spectrum\core\SpecContainerDescribe();

		$parent1->addSpec($spec);
		$parent2->addSpec($parent1);

		$this->assertSame($parent2, $spec->selector->getNearestNotContextAncestor());
	}

	public function testGetNearestNotContextAncestor_ShouldBeCheckFirstParent()
	{
		$spec = new \net\mkharitonov\spectrum\core\SpecContainerContext();
		$parent1 = new \net\mkharitonov\spectrum\core\SpecContainerDescribe();
		$parent2 = new \net\mkharitonov\spectrum\core\SpecContainerDescribe();

		$parent1->addSpec($spec);
		$parent2->addSpec($parent1);

		$this->assertSame($parent1, $spec->selector->getNearestNotContextAncestor());
	}

	public function testGetNearestNotContextAncestor_ShouldBeReturnNullIfNoParentNotContext()
	{
		$spec = new \net\mkharitonov\spectrum\core\SpecContainerContext();
		$parent = new \net\mkharitonov\spectrum\core\SpecContainerContext();
		$parent->addSpec($spec);
		$this->assertNull($spec->selector->getNearestNotContextAncestor());
	}

/**/

//	public function testGetRunningContextNested_ShouldBeReturnNullIfHasNoRunningContexts()
//	{
//		$spec = new SpecContainerDescribe();
//		$this->assertNull($spec->selector->getRunningContextNested());
//	}
//
//	public function testGetRunningContextNested_ShouldBeReturnRunningDescendantFromRunningChildren()
//	{
//		$describe = new SpecContainerDescribe();
//		$context1 = new SpecContainerContext();
//		$context2 = new SpecContainerContext();
//		$contextNested = new SpecContainerContext();
//		$it = new SpecItemIt();
//		$it->setTestCallback(function() use($describe, $context1, $context2, $contextNested)
//		{
//			\net\mkharitonov\spectrum\Test::$tmp['asserts'][] = array($contextNested, $describe->selector->getRunningContextNested());
//			\net\mkharitonov\spectrum\Test::$tmp['asserts'][] = array($contextNested, $context1->selector->getRunningContextNested());
//		});
//
//		$describe->addSpec($context1);
//		$describe->addSpec($context2);
//		$context1->addSpec($contextNested);
//		$contextNested->addSpec($it);
//
//		$describe->run();
//		$this->executeAssertsInStackSame(2);
//	}
//
//	public function testGetRunningContextNested_ShouldBeReturnNullIfHasHoDirectChildContexts()
//	{
//		$rootDescribe = new SpecContainerDescribe();
//		$describe = new SpecContainerDescribe();
//		$context = new SpecContainerContext();
//		$it = new SpecItemIt();
//		$it->setTestCallback(function() use($rootDescribe)
//		{
//			\net\mkharitonov\spectrum\Test::$tmp['asserts'][] = array(null, $rootDescribe->selector->getRunningContextNested());
//		});
//
//		$rootDescribe->addSpec($describe);
//		$describe->addSpec($context);
//		$context->addSpec($it);
//
//		$describe->run();
//		$this->executeAssertsInStackSame(1);
//	}

	public function testGetChildRunningContext_ShouldBeReturnOnlyFirstLevelChildRunningContext()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context(runningContext1)
			->Context(runningContext2)
			->->Context
			->It(it)
		');

		$callResults = array();
		$specs['it']->setTestCallback(function() use($specs, &$callResults){
			$callResults[] = $specs['it']->getParent()->selector->getChildRunningContext();
		});

		$specs[0]->run();

		$this->assertSame(array(
			$specs['runningContext1'],
			$specs['runningContext2'],
		), $callResults);
	}

	public function testGetChildRunningContext_ShouldBeReturnNullIfHasNoChildContexts()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It(it)
		');

		$callResults = array();
		$specs['it']->setTestCallback(function() use($specs, &$callResults){
			$callResults[] = $specs['it']->getParent()->selector->getChildRunningContext();
		});

		$specs[0]->run();

		$this->assertSame(array(null), $callResults);
	}

	public function testGetChildRunningContext_ShouldBeReturnNullIfHasNoRunningChildContexts()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->It(it)
		');

		// No run

		$this->assertNull($specs['it']->getParent()->selector->getChildRunningContext());
	}

/**/

	public function testGetDeepChildRunningContext_ChildContextHasNoDescendants_ShouldBeReturnChildRunningContext()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context(runningContext1)
			->Context(runningContext2)
			->It(it)
		');

		$callResults = array();
		$specs['it']->setTestCallback(function() use($specs, &$callResults){
			$callResults[] = $specs['it']->getParent()->selector->getDeepChildRunningContext();
		});

		$specs[0]->run();

		$this->assertSame(array(
			$specs['runningContext1'],
			$specs['runningContext2'],
		), $callResults);
	}

	public function testGetDeepChildRunningContext_ChildContextHasDescendants_ShouldBeReturnDeepRunningContext()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context(runningContext1)
			->Context
			->->Context
			->->->Context(runningContext2)
			->It(it)
		');

		$callResults = array();
		$specs['it']->setTestCallback(function() use($specs, &$callResults){
			$callResults[] = $specs['it']->getParent()->selector->getDeepChildRunningContext();
		});

		$specs[0]->run();

		$this->assertSame(array(
			$specs['runningContext1'],
			$specs['runningContext2'],
		), $callResults);
	}

	public function testGetDeepChildRunningContext_ShouldBeReturnNullIfHasNoChildContexts()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It(it)
		');

		$callResults = array();
		$specs['it']->setTestCallback(function() use($specs, &$callResults){
			$callResults[] = $specs['it']->getParent()->selector->getDeepChildRunningContext();
		});

		$specs[0]->run();

		$this->assertSame(array(null), $callResults);
	}

	public function testGetDeepChildRunningContext_ShouldBeReturnNullIfHasNoRunningChildContexts()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->It(it)
		');

		// No run

		$this->assertNull($specs['it']->getParent()->selector->getDeepChildRunningContext());
	}

/**/

	public function testGetChildRunningContextsStack_ChildContextHasNoDescendants_ShouldBeReturnArrayWithOneRunningContext()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context(runningContext1)
			->Context(runningContext2)
			->It(it)
		');

		$callResults = array();
		$specs['it']->setTestCallback(function() use($specs, &$callResults){
			$callResults[] = $specs['it']->getParent()->selector->getChildRunningContextsStack();
		});

		$specs[0]->run();

		$this->assertSame(array(
			array($specs['runningContext1']),
			array($specs['runningContext2']),
		), $callResults);
	}

	public function testGetChildRunningContextsStack_ChildContextHasDescendants_ShouldBeReturnArrayWithAllDescendantRunningContextsFromParentToChild()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context(stack1_context1)
			->->Context(stack1_context2)
			->Context(stack2_context1)
			->->Context(stack2_context2)
			->->->Context(stack2_context3)
			->It(it)
		');

		$callResults = array();
		$specs['it']->setTestCallback(function() use($specs, &$callResults){
			$callResults[] = $specs['it']->getParent()->selector->getChildRunningContextsStack();
		});

		$specs[0]->run();

		$this->assertSame(array(
			array($specs['stack1_context1'], $specs['stack1_context2']),
			array($specs['stack2_context1'], $specs['stack2_context2'], $specs['stack2_context3']),
		), $callResults);
	}

	public function testGetChildRunningContextsStack_ShouldBeReturnEmptyArrayIfHasNoChildContexts()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It(it)
		');

		$callResults = array();
		$specs['it']->setTestCallback(function() use($specs, &$callResults){
			$callResults[] = $specs['it']->getParent()->selector->getChildRunningContextsStack();
		});

		$specs[0]->run();

		$this->assertSame(array(array()), $callResults);
	}

	public function testGetChildRunningContextsStack_ShouldBeReturnEmptyArrayIfHasNoRunningChildContexts()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->It(it)
		');

		// No run

		$this->assertSame(array(), $specs['it']->getParent()->selector->getChildRunningContextsStack());
	}

/**/

	public function testGetAncestorsStack_ShouldBeReturnArrayWithAllAncestorsFromParentToChild()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Describe
			->->->It(it)
		');

		$specs['it']->setTestCallback(function() use($specs, &$stack){
			$stack = $specs['it']->selector->getAncestorsStack();
		});

		$specs[0]->run();

		$this->assertSame(array(
			$specs[0],
			$specs[1],
			$specs[2],
		), $stack);
	}

	public function testGetAncestorsStack_ShouldBeReturnEmptyArrayIfHasNoAncestors()
	{
		$specs = $this->createSpecsTree('
			It
		');

		$specs[0]->run();

		$this->assertSame(array(), $specs[0]->selector->getAncestorsStack());
	}

/**/

	public function testGetAncestorsWithRunningContextsStack_HasNoContexts_ShouldBeReturnArrayWithAllAncestorsFromParentToChild()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Describe
			->->->It(it)
		');

		$specs['it']->setTestCallback(function() use($specs, &$stack){
			$stack = $specs['it']->selector->getAncestorsWithRunningContextsStack();
		});

		$specs[0]->run();

		$this->assertSame(array(
			$specs[0],
			$specs[1],
			$specs[2],
		), $stack);
	}

	public function testGetAncestorsWithRunningContextsStack_HasOnlyChildContexts_ShouldBeAppendAllChildRunningContextsToEachAncestor()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->Context
			->Describe
			->->Context
			->->Describe
			->->->Context
			->->->It(it)
		');

		$callResults = array();
		$specs['it']->setTestCallback(function() use($specs, &$callResults){
			$callResults[] = $specs['it']->selector->getAncestorsWithRunningContextsStack();
		});

		$specs[0]->run();

		$this->assertSame(array(
			array($specs[0], $specs[1], $specs[3], $specs[4], $specs[5], $specs[6]),
			array($specs[0], $specs[2], $specs[3], $specs[4], $specs[5], $specs[6]),
		), $callResults);
	}

	public function testGetAncestorsWithRunningContextsStack_HasDeepChildContexts_ShouldBeAppendAllDescendantRunningContextsFromParentToChildToEachAncestor()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context
			->->->Context
			->Context
			->->Context
			->Describe
			->->Context
			->->->Context
			->->It(it)
		');

		$callResults = array();
		$specs['it']->setTestCallback(function() use($specs, &$callResults){
			$callResults[] = $specs['it']->selector->getAncestorsWithRunningContextsStack();
		});

		$specs[0]->run();

		$this->assertSame(array(
			array($specs[0], $specs[1], $specs[2], $specs[3], $specs[6], $specs[7], $specs[8]),
			array($specs[0], $specs[4], $specs[5], $specs[6], $specs[7], $specs[8]),
		), $callResults);
	}

	public function testGetAncestorsWithRunningContextsStack_ShouldBeReturnEmptyArrayIfHasNoAncestors()
	{
		$specs = $this->createSpecsTree('
			It
		');

		$specs[0]->run();

		$this->assertSame(array(), $specs[0]->selector->getAncestorsWithRunningContextsStack());
	}

/**/

	public function testGetChildrenWithName_ShouldBeReturnArrayWithChildrenWithSameName()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It
			->It
			->It(bar)
		');

		$specs[1]->setName('foo');
		$specs[2]->setName('foo');

		$this->assertSame(array(
			$specs[1],
			$specs[2],
		), $specs[0]->selector->getChildrenWithName('foo'));
	}

	public function testGetChildrenWithName_ShouldBeRestoreSourceIndex()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It(foo)
			->It
			->It
			->It(baz)
		');

		$specs[2]->setName('bar');
		$specs[3]->setName('bar');

		$this->assertSame(array(
			1 => $specs[2],
			2 => $specs[3],
		), $specs[0]->selector->getChildrenWithName('bar'));
	}

	public function testGetChildrenWithName_ShouldBeReturnEmptyArrayIfHasNoChildrenWithSameName()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It(foo)
			->It(bar)
		');

		$this->assertSame(array(), $specs[0]->selector->getChildrenWithName('baz'));
	}

/**/

	public function testGetChildByName_ShouldBeReturnOnlyFirstSpecWithSameName()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It
			->It
			->It(bar)
		');

		$specs[1]->setName('foo');
		$specs[2]->setName('foo');

		$this->assertSame($specs[1], $specs[0]->selector->getChildByName('foo'));
	}

	public function testGetChildByName_ShouldBeReturnNullIfSpecWithSameNameNotExists()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It(foo)
			->It(bar)
		');

		$this->assertNull($specs[0]->selector->getChildByName('baz'));
	}

/**/

	public function testGetChildByIndex_ShouldBeReturnSpecWithSameIndex()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It
			->It
			->It
		');

		$this->assertSame($specs[2], $specs[0]->selector->getChildByIndex(1));
	}

	public function testGetChildByIndex_ShouldBeReturnNullIfSpecWithSameNameNotExists()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It
		');

		$this->assertNull($specs[0]->selector->getChildByIndex(99));
	}
}