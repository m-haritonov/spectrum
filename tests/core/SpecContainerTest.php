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

namespace net\mkharitonov\spectrum\core;
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class SpecContainerTest extends SpecTest
{
	public function testClone_ShouldBeCloneChildren()
	{
		$spec = $this->createCurrentSpec();

		$child1 = new SpecContainerDescribe();
		$child2 = new SpecContainerContext();
		$child3 = new SpecItemIt();

		$spec->addSpec($child1);
		$spec->addSpec($child2);
		$spec->addSpec($child3);

		$cloneSpec = clone $spec;
		$cloneChildren = $cloneSpec->getSpecs();

		$this->assertEquals(3, count($cloneChildren));

		$this->assertNotSame($child1, $cloneChildren[0]);
		$this->assertNotSame($child2, $cloneChildren[1]);
		$this->assertNotSame($child3, $cloneChildren[2]);

		$this->assertTrue($cloneChildren[0] instanceof SpecContainerDescribeInterface);
		$this->assertTrue($cloneChildren[1] instanceof SpecContainerContextInterface);
		$this->assertTrue($cloneChildren[2] instanceof SpecItemIt);
	}

	public function testClone_ShouldBeChangeChildrenParent()
	{
		$spec = $this->createCurrentSpec();

		$child1 = new SpecContainerDescribe();
		$child2 = new SpecContainerContext();
		$child3 = new SpecItemIt();

		$spec->addSpec($child1);
		$spec->addSpec($child2);
		$spec->addSpec($child3);

		$cloneSpec = clone $spec;
		$cloneChildren = $cloneSpec->getSpecs();

		$this->assertSame($cloneSpec, $cloneChildren[0]->getParent());
		$this->assertSame($cloneSpec, $cloneChildren[1]->getParent());
		$this->assertSame($cloneSpec, $cloneChildren[2]->getParent());
	}

/**/

	public function testSetName_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[0]->errorHandling->setCatchExceptions(false);
		$specs[1]->setTestCallback(function() use($specs){
			$specs[0]->setName('foo');
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($specs){
			$specs[0]->run();
		});
	}

/**/

	public function testSetParent_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[0]->errorHandling->setCatchExceptions(false);
		$specs[1]->setTestCallback(function() use($specs){
			$specs[0]->setParent(new SpecContainerDescribe());
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($specs){
			$specs[0]->run();
		});
	}

/**/

	public function testRemoveFromParent_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[0]->errorHandling->setCatchExceptions(false);
		$specs[1]->setTestCallback(function() use($specs){
			$specs[0]->removeFromParent();
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($specs){
			$specs[0]->run();
		});
	}

/**/

	public function testEnable_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[0]->errorHandling->setCatchExceptions(false);
		$specs[1]->setTestCallback(function() use($specs){
			$specs[0]->enable();
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($specs){
			$specs[0]->run();
		});
	}

/**/

	public function testDisable_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[0]->errorHandling->setCatchExceptions(false);
		$specs[1]->setTestCallback(function() use($specs){
			$specs[0]->disable();
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($specs){
			$specs[0]->run();
		});
	}

/**/

	public function testAddSpec_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[0]->errorHandling->setCatchExceptions(false);
		$specs[1]->setTestCallback(function() use($specs){
			$specs[0]->addSpec(new SpecContainerDescribe());
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($specs){
			$specs[0]->run();
		});
	}

/**/

	public function testRemoveSpec_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[0]->errorHandling->setCatchExceptions(false);
		$specs[1]->setTestCallback(function() use($specs){
			$specs[0]->removeSpec(new SpecContainerDescribe());
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($specs){
			$specs[0]->run();
		});
	}

/**/

	public function testRemoveAllSpecs_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[0]->errorHandling->setCatchExceptions(false);
		$specs[1]->setTestCallback(function() use($specs){
			$specs[0]->removeAllSpecs();
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($specs){
			$specs[0]->run();
		});
	}

/**/

	public function testGetSpecs_ShouldBeReturnEmptyArrayByDefault()
	{
		$spec = $this->createCurrentSpec();
		$this->assertSame(array(), $spec->getSpecs());
	}

	public function testAddSpecs_ShouldBeAcceptAnySpecs()
	{
		$spec = $this->createCurrentSpec();

		$child1 = new SpecContainerDescribe();
		$child2 = new SpecContainerDescribe();
		$child3 = new SpecContainerContext();
		$child4 = new SpecItemIt();

		$spec->addSpec($child1);
		$this->assertSame(array($child1), $spec->getSpecs());

		$spec->addSpec($child2);
		$this->assertSame(array($child1, $child2), $spec->getSpecs());

		$spec->addSpec($child3);
		$this->assertSame(array($child1, $child2, $child3), $spec->getSpecs());

		$spec->addSpec($child4);
		$this->assertSame(array($child1, $child2, $child3, $child4), $spec->getSpecs());
	}

	public function testAddSpecs_ShouldBeSetSelfAsParentToChildren()
	{
		$spec = $this->createCurrentSpec();

		$child1 = new SpecContainerDescribe();
		$child2 = new SpecContainerDescribe();
		$child3 = new SpecContainerContext();
		$child4 = new SpecItemIt();

		$spec->addSpec($child1);
		$spec->addSpec($child2);
		$spec->addSpec($child3);
		$spec->addSpec($child4);

		$this->assertSame($spec, $child1->getParent());
		$this->assertSame($spec, $child2->getParent());
		$this->assertSame($spec, $child3->getParent());
		$this->assertSame($spec, $child4->getParent());
	}

/**/

	public function testIsRunning_ShouldBeReturnTrueDuringRunning()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');
		$isRunning = null;
		$specs[1]->setTestCallback(function() use($specs, &$isRunning) { $isRunning = $specs[0]->isRunning(); });

		$specs[0]->run();

		$this->assertTrue($isRunning);
	}

	public function testIsRunning_ShouldBeReturnFalseIfSpecNotRunning()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$this->assertFalse($specs[0]->isRunning());
	}

	public function testIsRunning_ShouldBeReturnFalseAfterRunning()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');
		$specs[1]->setTestCallback(function(){});

		$specs[0]->run();
		
		$this->assertFalse($specs[0]->isRunning());
	}

/**/

//	public function testGetRunningContext_ShouldBeReturnNullIfHasNoRunningContexts()
//	{
//		$this->assertNull($this->createCurrentSpec()->getRunningContext());
//	}

/**/

	public function testRun_ShouldNotBeIgnoreDisabledContextsForRunModeDetection() // Because enable/disable should not be change behaviour
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->Context
			->ItMock
		');

		$specs[1]->disable();
		$this->injectToRunStartSaveInstanceToCollection($specs[2]);

		$specs[0]->run();
		$this->assertInstanceNotInCollection($specs[2]);
	}

/**/

	public function testRun_HasChildContexts_ShouldBeRunAllEnabledContexts()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->ContextMock
			->ContextMock
			->ContextMock
		');

		$this->injectToRunStartSaveInstanceToCollection($specs[1]);
		$this->injectToRunStartSaveInstanceToCollection($specs[2]);
		$this->injectToRunStartSaveInstanceToCollection($specs[3]);

		$specs[0]->run();
		$this->assertInstanceInCollection($specs[1]);
		$this->assertInstanceInCollection($specs[2]);
		$this->assertInstanceInCollection($specs[3]);
	}

	public function testRun_HasChildContexts_ShouldNotBeRunDisabledContexts()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->ContextMock
		');

		$specs[1]->disable();
		$this->injectToRunStartSaveInstanceToCollection($specs[1]);

		$specs[0]->run();
		$this->assertInstanceNotInCollection($specs[1]);
	}

	public function testRun_HasChildContexts_ShouldNotBeRunAnyNotContextChildren()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->Context
			->DescribeMock
			->ItMock
		');

		$specs[1]->disable();
		$this->injectToRunStartSaveInstanceToCollection($specs[2]);
		$this->injectToRunStartSaveInstanceToCollection($specs[3]);

		$specs[0]->run();
		$this->assertInstanceNotInCollection($specs[2]);
		$this->assertInstanceNotInCollection($specs[3]);
	}

	public function testRun_HasChildContexts_ReturnValue_ShouldBeReturnFalseIfAnyContextIsFail()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->ContextMock
			->ContextMock
			->ContextMock
			->ContextMock
		');

		$specs[1]->__disableRealRunCall();
		$specs[2]->__disableRealRunCall();
		$specs[3]->__disableRealRunCall();
		$specs[4]->__disableRealRunCall();

		$specs[1]->__setRunReturnValue(true);
		$specs[2]->__setRunReturnValue(false);
		$specs[3]->__setRunReturnValue(null);
		$specs[4]->__setRunReturnValue(true);

		$this->assertFalse($specs[0]->run());
	}

	public function testRun_HasChildContexts_ReturnValue_FirstChildSpecResultIsNull_ShouldBeReturnFalseIfAnyContextIsFail()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->ContextMock
			->ContextMock
			->ContextMock
			->ContextMock
		');

		$specs[1]->__disableRealRunCall();
		$specs[2]->__disableRealRunCall();
		$specs[3]->__disableRealRunCall();
		$specs[4]->__disableRealRunCall();

		$specs[1]->__setRunReturnValue(null);
		$specs[2]->__setRunReturnValue(false);
		$specs[4]->__setRunReturnValue(true);

		$this->assertFalse($specs[0]->run());
	}

	public function testRun_HasChildContexts_ReturnValue_ShouldBeReturnTrueOnlyIfAllContextsIsSuccess()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->ContextMock
			->ContextMock
		');

		$specs[1]->__disableRealRunCall();
		$specs[2]->__disableRealRunCall();

		$specs[1]->__setRunReturnValue(true);
		$specs[2]->__setRunReturnValue(true);

		$this->assertTrue($specs[0]->run());
	}

	public function testRun_HasChildContexts_ReturnValue_ShouldBeReturnNullIfAnyContextIsReturnEmptyResultAndNoFailContexts()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->ContextMock
			->ContextMock
			->ContextMock
		');

		$specs[1]->__disableRealRunCall();
		$specs[2]->__disableRealRunCall();
		$specs[3]->__disableRealRunCall();

		$specs[1]->__setRunReturnValue(true);
		$specs[2]->__setRunReturnValue(null);
		$specs[3]->__setRunReturnValue(true);

		$this->assertNull($specs[0]->run());
	}

/**/

	public function testRun_HasNoChildContexts_ReturnValue_ShouldBeReturnFalseIfAnyChildrenIsFail()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->DescribeMock
			->DescribeMock
			->ItMock
			->ItMock
		');

		$specs[1]->__disableRealRunCall();
		$specs[2]->__disableRealRunCall();
		$specs[3]->__disableRealRunCall();
		$specs[4]->__disableRealRunCall();

		$specs[1]->__setRunReturnValue(true);
		$specs[2]->__setRunReturnValue(false);
		$specs[3]->__setRunReturnValue(null);
		$specs[4]->__setRunReturnValue(true);

		$this->assertFalse($specs[0]->run());
	}

	public function testRun_HasNoChildContexts_ReturnValue_FirstChildSpecResultIsNull_ShouldBeReturnFalseIfAnyChildrenIsFail()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->DescribeMock
			->DescribeMock
			->ItMock
			->ItMock
		');

		$specs[1]->__disableRealRunCall();
		$specs[2]->__disableRealRunCall();
		$specs[3]->__disableRealRunCall();
		$specs[4]->__disableRealRunCall();

		$specs[1]->__setRunReturnValue(null);
		$specs[2]->__setRunReturnValue(false);
		$specs[4]->__setRunReturnValue(true);

		$this->assertFalse($specs[0]->run());
	}

	public function testRun_HasNoChildContexts_ReturnValue_ShouldBeReturnTrueOnlyIfAllChildrenIsSuccess()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->DescribeMock
			->ItMock
		');

		$specs[1]->__disableRealRunCall();
		$specs[2]->__disableRealRunCall();

		$specs[1]->__setRunReturnValue(true);
		$specs[2]->__setRunReturnValue(true);

		$this->assertTrue($specs[0]->run());
	}

	public function testRun_HasNoChildContexts_ReturnValue_ShouldBeReturnNullIfAnyChildrenIsReturnEmptyResultAndNoFailChildren()
	{
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->DescribeMock
			->DescribeMock
			->ItMock
		');

		$specs[1]->__disableRealRunCall();
		$specs[2]->__disableRealRunCall();
		$specs[3]->__disableRealRunCall();

		$specs[1]->__setRunReturnValue(true);
		$specs[2]->__setRunReturnValue(null);
		$specs[3]->__setRunReturnValue(true);

		$this->assertNull($specs[0]->run());
	}
}