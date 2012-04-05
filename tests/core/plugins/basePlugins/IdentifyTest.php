<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;
use spectrum\core\SpecItemIt;

require_once dirname(__FILE__) . '/../../../init.php';

class IdentifyTest extends Test
{
	/**
	 * @dataProvider dataProviderSpecsTreeToSpecId_WithoutContexts
	 */
	public function testGetSpecId_DeclaringState($specsTree, $specId)
	{
		$specs = $this->createSpecsTree($specsTree);
		$this->assertSame($specId, $specs['spec']->identify->getSpecId());
	}

	/**
	 * @dataProvider dataProviderSpecsTreeToSpecId_WithContexts
	 */
	public function testGetSpecId_RunningState($specsTree, $specId)
	{
		$specs = $this->createSpecsTree($specsTree);
		$specs['spec']->setTestCallback(function() use(&$result, $specs){
			$result = $specs['spec']->identify->getSpecId();
		});

		$specs['spec']->run();
		$this->assertSame($specId, $result);
	}

/**/

	/**
	 * @dataProvider dataProviderSpecsTreeToSpecId_WithoutContexts
	 */
	public function testGetSpecById_DeclaringState_ShouldBeReturnProperSpecInstance($specsTree, $specId)
	{
		$specs = $this->createSpecsTree($specsTree, array(), true);
		$this->assertSame($specs['spec'], $specs[0]->identify->getSpecById($specId));
	}

	/**
	 * @dataProvider dataProviderSpecsTreeToSpecId_WithContexts
	 */
	public function testGetSpecById_RunningState_ShouldBeReturnProperSpecInstance($specsTree, $specId)
	{
		$specs = $this->createSpecsTree($specsTree, array(), true);
		$specs['spec']->setTestCallback(function() use(&$result, $specs, $specId){
			$result = $specs[0]->identify->getSpecById($specId);
		});

		$specs['spec']->run();
		$this->assertSame($specs['spec'], $result);
	}

	public function testGetSpecById_ShouldBeIgnoreStartingAndEndingSpaces()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->It(spec)
		');

		$this->assertSame($specs['spec'], $specs[0]->identify->getSpecById("\r\n\t   spec0x1\r\n\t   "));
		$this->assertSame($specs['spec'], $specs[0]->identify->getSpecById("\r\n\t   spec0x1_c0\r\n\t   "));
	}

	public function testGetSpecById_ShouldBeThrowExceptionIfSpecIdNotStartedWithSpecString()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Incorrect spec id "foo0" (id should be started with "spec" string)', function(){
			$spec = new SpecItemIt();
			$spec->identify->getSpecById('foo0');
		});
	}

	public function testGetSpecById_ShouldBeThrowExceptionIfSpecIdContainsDenySymbols()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Incorrect spec id "spec0b0" (id should be contains only "spec" string, chars "x", "c", "_" and digits)', function(){
			$spec = new SpecItemIt();
			$spec->identify->getSpecById('spec0b0');
		});
	}

	public function testGetSpecById_ShouldBeThrowExceptionIfSpecIdHasIncorrectFormat()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Incorrect spec id "spec0x" (id should be in format like "spec0x1" or "spec0x1_c0c0")', function(){
			$spec = new SpecItemIt();
			$spec->identify->getSpecById('spec0x');
		});
	}

	public function testGetSpecById_ShouldBeThrowExceptionIfFirstIndexNotZero()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Incorrect spec id "spec1x0" (first index in id should be "0")', function(){
			$spec = new SpecItemIt();
			$spec->identify->getSpecById('spec1x0');
		});
	}

	public function testGetSpecById_ShouldBeThrowExceptionIfSomeSpecNotExists()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It(spec)
		');

		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Incorrect spec id "spec0x999x0" (spec with index "999" on "2" position in id not exists)', function() use($specs){
			$specs['spec']->identify->getSpecById('spec0x999x0');
		});
	}

/**/

	/**
	 * @dataProvider dataProviderSpecsTreeToSpecId_WithoutContexts
	 */
//	public function testGetContextsById_WithoutContexts_ShouldBeReturnEmptyArray($specsTree, $specId)
//	{
//		$specs = $this->createSpecsTree($specsTree, array(), true);
//		$this->assertSame(array(), $specs[0]->identify->getContextsById($specId));
//	}
//
//	public function testGetContextsById_WithContexts_ShouldBeReturnProperContextInstances()
//	{
//		$specs = $this->createSpecsTree('
//			Describe
//			->Context(context1)
//			->->Context(context2)
//			->Describe
//			->->Context(context3)
//			->->->Context(context4)
//			->->It
//		');
//
//		$this->assertSame(array(
//			array($specs['context1'], $specs['context2']),
//			array($specs['context3'], $specs['context4']),
//		), $specs[0]->identify->getContextsById('spec0x1x1_c0x0c0x0c'));
//	}
//
//	public function testGetContextsById_ShouldBeIgnoreStartingAndEndingSpaces()
//	{
//		$specs = $this->createSpecsTree('
//			Describe
//			->Context
//			->It(spec)
//		');
//
//		$this->assertSame($specs['spec'], $specs[0]->identify->getContextsById("\r\n\t   spec0x1\r\n\t   "));
//		$this->assertSame($specs['spec'], $specs[0]->identify->getContextsById("\r\n\t   spec0x1_c0\r\n\t   "));
//	}
//
//	public function testGetContextsById_ShouldBeThrowExceptionIfSpecIdNotStartedWithSpecString()
//	{
//		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Incorrect spec id "foo0" (id should be started with "spec" string)', function(){
//			$spec = new SpecItemIt();
//			$spec->identify->getContextsById('foo0_c0x1');
//		});
//	}
//
//	public function testGetContextsById_ShouldBeThrowExceptionIfSpecIdContainsDenySymbols()
//	{
//		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Incorrect spec id "spec0b0" (id should be contains only "spec" string, chars "x", "c", "_" and digits)', function(){
//			$spec = new SpecItemIt();
//			$spec->identify->getContextsById('spec0b0_c0x1');
//		});
//	}
//
//	public function testGetContextsById_ShouldBeThrowExceptionIfSpecIdHasIncorrectFormat()
//	{
//		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Incorrect spec id "spec0x" (id should be in format like "spec0x1" or "spec0x1_c0c0")', function(){
//			$spec = new SpecItemIt();
//			$spec->identify->getContextsById('spec0x_c0x1');
//		});
//	}
//
//	public function testGetContextsById_ShouldBeThrowExceptionIfFirstIndexNotZero()
//	{
//		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Incorrect spec id "spec1x0" (first index in id should be "0")', function(){
//			$spec = new SpecItemIt();
//			$spec->identify->getContextsById('spec1x0_c0x1');
//		});
//	}
//
//	public function testGetContextsById_ShouldBeThrowExceptionIfSomeSpecNotExists()
//	{
//		$specs = $this->createSpecsTree('
//			Describe
//			->It(spec)
//		');
//
//		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Incorrect spec id "spec0x999x0" (spec with index "999" on "2" position in id not exists)', function() use($specs){
//			$specs['spec']->identify->getContextsById('spec0x999x0_c0x1');
//		});
//	}

/* Data providers */

	public function dataProviderSpecsTreeToSpecId_WithoutContexts()
	{
		return array(
			array('It(spec)', 'spec0'),

			// Describe level 1
			array('
				Describe
				->It(spec)
			', 'spec0x0'),

			array('
				Describe
				->It
				->It(spec)
			', 'spec0x1'),

			// Describe level 2
			array('
				Describe
				->Describe
				->->It(spec)
			', 'spec0x0x0'),

			array('
				Describe
				->Describe
				->Describe
				->->It
				->->It(spec)
			', 'spec0x1x1'),

			// Context level 2
			array('
				Context
				->Context
				->->It(spec)
			', 'spec0x0x0'),

			array('
				Context
				->Context
				->Context
				->->It
				->->It(spec)
			', 'spec0x1x1'),
		);
	}

	public function dataProviderSpecsTreeToSpecId_WithContexts()
	{
		return array(
			array('It(spec)', 'spec0_c'),

			// Describe level 1, no contexts
			array('
				Describe
				->It(spec)
			', 'spec0x0_cc'),

			array('
				Describe
				->It
				->It(spec)
			', 'spec0x1_cc'),

			// Describe level 2, no contexts
			array('
				Describe
				->Describe
				->->It(spec)
			', 'spec0x0x0_ccc'),

			array('
				Describe
				->Describe
				->Describe
				->->It
				->->It(spec)
			', 'spec0x1x1_ccc'),

			// Describe level 1, with contexts
			array('
				Describe
				->Context
				->It(spec)
			', 'spec0x1_c0c'),

			array('
				Describe
				->Context
				->It
				->It(spec)
			', 'spec0x2_c0c'),

			// Describe level 2, with contexts
			array('
				Describe
				->Context
				->Describe
				->->It
				->->It(spec)
				->->Context
			', 'spec0x1x1_c0c2c'),

			// Describe level 2, with contexts, many specs
			array('
				Describe
				->Context
				->Describe
				->Describe
				->Describe
				->Describe
				->Describe
				->Describe
				->Describe
				->Describe
				->Describe
				->Describe
				->Describe
				->->It
				->->It
				->->It
				->->It
				->->It
				->->It
				->->It
				->->It
				->->It
				->->It
				->->It
				->->It
				->->It
				->->It
				->->It
				->->It
				->->It(spec)
				->->Context
			', 'spec0x11x16_c0c17c'),
		);
	}
}