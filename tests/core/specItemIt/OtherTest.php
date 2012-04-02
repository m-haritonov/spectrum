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

namespace net\mkharitonov\spectrum\core\specItemIt;
use net\mkharitonov\spectrum\core\SpecItemIt;
use net\mkharitonov\spectrum\core\Config;
use \net\mkharitonov\spectrum\core\SpecContainerDescribe;

require_once dirname(__FILE__) . '/../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class OtherTest extends Test
{
	public function testConstructor_ShouldBeCanAcceptNoArguments()
	{
		$it = new SpecItemIt();

		$this->assertNull($it->getName());
		$this->assertNull($it->getTestCallback());
		$this->assertSame(array(), $it->getAdditionalArguments());
	}

/**/

	public function testSetName_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);

		$spec = $this->createCurrentSpec();
		$spec->errorHandling->setCatchExceptions(false);
		$spec->setTestCallback(function() use($spec){
			$spec->setName('foo');
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
			$spec->run();
		});
	}

	public function testHandleSpecModifyDeny_ShouldBeDetectRunningStateByRootSpec()
	{
		Config::setAllowSpecsModifyWhenRunning(false);

		$specs = $this->createSpecsTree('
			Describe
			->It
			->It
		');

		$specs[0]->errorHandling->setCatchExceptions(false);
		$specs[1]->setTestCallback(function() use($specs){
			$specs[2]->setName('foo');
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($specs){
			$specs[0]->run();
		});
	}

/**/

	public function testSetParent_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);

		$spec = $this->createCurrentSpec();
		$spec->errorHandling->setCatchExceptions(false);
		$spec->setTestCallback(function() use($spec){
			$spec->setParent(new SpecContainerDescribe());
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
			$spec->run();
		});
	}

/**/

	public function testRemoveFromParent_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);

		$spec = $this->createCurrentSpec();
		$spec->errorHandling->setCatchExceptions(false);
		$spec->setTestCallback(function() use($spec){
			$spec->removeFromParent();
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
			$spec->run();
		});
	}

/**/

	public function testEnable_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);

		$spec = $this->createCurrentSpec();
		$spec->errorHandling->setCatchExceptions(false);
		$spec->setTestCallback(function() use($spec){
			$spec->enable();
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
			$spec->run();
		});
	}

/**/

	public function testDisable_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);

		$spec = $this->createCurrentSpec();
		$spec->errorHandling->setCatchExceptions(false);
		$spec->setTestCallback(function() use($spec){
			$spec->disable();
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
			$spec->run();
		});
	}

/**/

	public function testSetTestCallback_ShouldBeAcceptNull()
	{
		$it = new SpecItemIt();

		$it->setTestCallback(function(){ });
		$it->setTestCallback(null);

		$this->assertNull($it->getTestCallback());
	}

	public function testSetTestCallback_ShouldBeAcceptClosureFunction()
	{
		$it = new SpecItemIt();

		$it->setTestCallback(function() use($it) { $it->getRunResultsBuffer()->addResult(true); });

		$this->assertTrue($it->run());
	}

	public function testSetTestCallback_ShouldBeAcceptCreatedAnonymousFunction()
	{
		$it = new SpecItemIt();

		\net\mkharitonov\spectrum\Test::$tmp['testSpec'] = $it;
		$it->setTestCallback(create_function('', '\net\mkharitonov\spectrum\Test::$tmp[\'testSpec\']->getRunResultsBuffer()->addResult(true);'));

		$this->assertTrue($it->run());
	}

	public function testSetTestCallback_ShouldBeAcceptFunctionStringName()
	{
		$it = new SpecItemIt();

		\net\mkharitonov\spectrum\Test::$tmp['testSpec'] = $it;
		$it->setTestCallback(__CLASS__ . '::myTestCallback');

		$this->assertTrue($it->run());
	}

	public function testSetTestCallback_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);

		$spec = $this->createCurrentSpec();
		$spec->errorHandling->setCatchExceptions(false);
		$spec->setTestCallback(function() use($spec){
			$spec->setTestCallback(function(){});
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
			$spec->run();
		});
	}

/**/

	public function testGetTestCallback_ShouldBeReturnNullByDefault()
	{
		$it = new SpecItemIt();
		$this->assertNull($it->getTestCallback());
	}

	public function testGetTestCallback_ShouldBeReturnSourceValue()
	{
		$it = new SpecItemIt();

		$callback1 = function(){};
		$it->setTestCallback($callback1);

		$this->assertSame($callback1, $it->getTestCallback());
	}

/**/

	public function testSetAdditionalArguments_ShouldBeAcceptArray()
	{
		$it = new SpecItemIt();
		$it->setAdditionalArguments(array('foo', 'bar'));
		$this->assertSame(array('foo', 'bar'), $it->getAdditionalArguments());
	}

	public function testSetAdditionalArguments_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);

		$spec = $this->createCurrentSpec();
		$spec->errorHandling->setCatchExceptions(false);
		$spec->setTestCallback(function() use($spec){
			$spec->setAdditionalArguments(array());
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
			$spec->run();
		});
	}

/**/

	public function testGetAdditionalArguments_ShouldBeReturnEmptyArrayByDefault()
	{
		$it = new SpecItemIt();
		$this->assertSame(array(), $it->getAdditionalArguments());
	}

	public function testGetAdditionalArguments_ShouldBeReturnSourceValue()
	{
		$it = new SpecItemIt();
		$it->setAdditionalArguments(array('foo'));
		$this->assertSame(array('foo'), $it->getAdditionalArguments());
	}

/*** Test ware ***/

	static public function myTestCallback()
	{
		\net\mkharitonov\spectrum\Test::$tmp['testSpec']->getRunResultsBuffer()->addResult(true);
	}
}