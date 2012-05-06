<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specItemIt;
use spectrum\core\SpecItemIt;
use spectrum\core\Config;
use \spectrum\core\SpecContainerDescribe;

require_once dirname(__FILE__) . '/../../init.php';

class OtherTest extends Test
{
	public function testConstructor_ShouldBeCanAcceptNoArguments()
	{
		$it = new SpecItemIt();

		$this->assertNull($it->getName());
		$this->assertNull($it->getTestCallback());
		$this->assertSame(array(), $it->getTestCallbackArguments());
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

		$this->assertThrowException('\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
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

		$this->assertThrowException('\spectrum\core\Exception', 'Modify specs when running deny', function() use($specs){
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

		$this->assertThrowException('\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
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

		$this->assertThrowException('\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
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

		$this->assertThrowException('\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
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

		$this->assertThrowException('\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
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

	public function testSetTestCallback_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);

		$spec = $this->createCurrentSpec();
		$spec->errorHandling->setCatchExceptions(false);
		$spec->setTestCallback(function() use($spec){
			$spec->setTestCallback(function(){});
		});

		$this->assertThrowException('\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
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

	public function testSetTestCallbackArguments_ShouldBeAcceptArray()
	{
		$it = new SpecItemIt();
		$it->setTestCallbackArguments(array('foo', 'bar'));
		$this->assertSame(array('foo', 'bar'), $it->getTestCallbackArguments());
	}

	public function testSetTestCallbackArguments_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);

		$spec = $this->createCurrentSpec();
		$spec->errorHandling->setCatchExceptions(false);
		$spec->setTestCallback(function() use($spec){
			$spec->setTestCallbackArguments(array());
		});

		$this->assertThrowException('\spectrum\core\Exception', 'Modify specs when running deny', function() use($spec){
			$spec->run();
		});
	}

/**/

	public function testGetTestCallbackArguments_ShouldBeReturnEmptyArrayByDefault()
	{
		$it = new SpecItemIt();
		$this->assertSame(array(), $it->getTestCallbackArguments());
	}

	public function testGetTestCallbackArguments_ShouldBeReturnSourceValue()
	{
		$it = new SpecItemIt();
		$it->setTestCallbackArguments(array('foo'));
		$this->assertSame(array('foo'), $it->getTestCallbackArguments());
	}
}