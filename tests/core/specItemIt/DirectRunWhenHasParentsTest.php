<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specItemIt;
require_once __DIR__ . '/../../init.php';

class DirectRunWhenHasParentsTest extends Test
{
	public function testShouldBeReturnParentRunResult()
	{
		$specs = $this->createSpecsTree('
			DescribeMock
			->' . $this->currentSpecClass . '(testSpec)
		');

		$specs[0]->__setRunReturnValue('foo');
		$this->assertEquals('foo', $specs['testSpec']->run());
	}
	
	public function testShouldBeDisableAllNotContextSiblingsDuringRun()
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

	public function testShouldNotBeDisableContextSiblings()
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

	public function testShouldNotBeEnableDisabledContextSiblings()
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

	public function testShouldBeEnableSelfDuringRun()
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

	public function testShouldBeRestoreSiblingsEnabledStatusAfterRun()
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

	public function testShouldBeRestoreSiblingsDisabledStatusAfterRun()
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

	public function testShouldBeRestoreSelfEnabledStatusAfterRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(testSpec)
		');

		$specs['testSpec']->run();

		$this->assertTrue($specs['testSpec']->isEnabled());
	}

	public function testShouldBeRestoreSelfDisabledStatusAfterRun()
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