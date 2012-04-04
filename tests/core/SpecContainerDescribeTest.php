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

namespace spectrum\core;
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class SpecContainerDescribeTest extends SpecContainerTest
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerDescribe';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecContainerDescribeMock';

/**/

	public function testRun_HasNoChildContexts_ShouldBeRunAllEnabledChildren()
	{
		$specs = $this->createSpecsTree('
			Describe
			->DescribeMock
			->ItMock
		');

		$this->injectToRunStartSaveInstanceToCollection($specs[1]);
		$this->injectToRunStartSaveInstanceToCollection($specs[2]);

		$specs[0]->run();
		$this->assertInstanceInCollection($specs[1]);
		$this->assertInstanceInCollection($specs[2]);
	}

	public function testRun_HasNoChildContexts_ShouldNotBeRunDisabledChildren()
	{
		$specs = $this->createSpecsTree('
			Describe
			->DescribeMock
			->ItMock
		');

		$specs[1]->disable();
		$specs[2]->disable();
		$this->injectToRunStartSaveInstanceToCollection($specs[1]);
		$this->injectToRunStartSaveInstanceToCollection($specs[2]);

		$specs[0]->run();
		$this->assertInstanceNotInCollection($specs[1]);
		$this->assertInstanceNotInCollection($specs[2]);
	}

/**/

	public function testRun_DirectRunWhenHasParents_ShouldBeReturnParentRunResult()
	{
		$specs = $this->createSpecsTree('
			DescribeMock
			->' . $this->currentSpecClass . '(testSpec)
		');

		$specs[0]->__setRunReturnValue('foo');
		$this->assertEquals('foo', $specs['testSpec']->run());
	}

	public function testRun_DirectRunWhenHasParents_ShouldBeDisableAllNotContextSiblingsDuringRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->DescribeMock
			->' . $this->currentSpecClass . '(testSpec)
			->ItMock
		');

		$this->injectToRunStartCallsCounter($specs[1]);
		$this->injectToRunStartCallsCounter($specs[3]);

		$specs['testSpec']->run();

		$this->assertEquals(0, (int) \spectrum\Test::$tmp['callsCounter']);
	}

	public function testRun_DirectRunWhenHasParents_ShouldNotBeDisableContextSiblings()
	{
		$specs = $this->createSpecsTree('
			Describe
			->ContextMock
			->' . $this->currentSpecClass . '(testSpec)
		');

		$this->injectToRunStartCallsCounter($specs[1]);

		$specs['testSpec']->run();
		$this->assertEquals(1, (int) \spectrum\Test::$tmp['callsCounter']);
	}

	public function testRun_DirectRunWhenHasParents_ShouldNotBeEnableDisabledContextSiblings()
	{
		$specs = $this->createSpecsTree('
			Describe
			->ContextMock
			->' . $this->currentSpecClass . '(testSpec)
		');

		$this->injectToRunStartCallsCounter($specs[1]);
		$specs[1]->disable();
		$specs['testSpec']->run();

		$this->assertEquals(0, (int) \spectrum\Test::$tmp['callsCounter']);
	}

	public function testRun_DirectRunWhenHasParents_ShouldBeEnableSelfDuringRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecMockClass . '(testSpec)
		');

		$this->injectToRunStartCallsCounter($specs['testSpec']);
		$specs['testSpec']->disable();

		$specs['testSpec']->run();
		$this->assertEquals(1, (int) \spectrum\Test::$tmp['callsCounter']);
	}

	public function testRun_DirectRunWhenHasParents_ShouldBeRestoreSiblingsEnabledStatusAfterRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->Describe
			->It
			->' . $this->currentSpecClass . '(testSpec)
		');

		$specs['testSpec']->run();

		$this->assertTrue($specs[1]->isEnabled());
		$this->assertTrue($specs[2]->isEnabled());
		$this->assertTrue($specs[3]->isEnabled());
	}

	public function testRun_DirectRunWhenHasParents_ShouldBeRestoreSiblingsDisabledStatusAfterRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->Describe
			->It
			->' . $this->currentSpecClass . '(testSpec)
		');

		$specs[1]->disable();
		$specs[2]->disable();
		$specs[3]->disable();

		$specs['testSpec']->run();

		$this->assertFalse($specs[1]->isEnabled());
		$this->assertFalse($specs[2]->isEnabled());
		$this->assertFalse($specs[3]->isEnabled());
	}

	public function testRun_DirectRunWhenHasParents_ShouldBeRestoreSelfEnabledStatusAfterRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(testSpec)
		');

		$specs['testSpec']->run();

		$this->assertTrue($specs['testSpec']->isEnabled());
	}

	public function testRun_DirectRunWhenHasParents_ShouldBeRestoreSelfDisabledStatusAfterRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(testSpec)
		');

		$specs['testSpec']->disable();
		$specs['testSpec']->run();

		$this->assertFalse($specs['testSpec']->isEnabled());
	}
}