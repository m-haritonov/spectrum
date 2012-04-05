<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specItemIt\errorHandling\catchPhpErrors;
require_once dirname(__FILE__) . '/../../../../init.php';

class DisabledTest extends Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->it->errorHandling->setCatchPhpErrors(false);
		$this->it->errorHandling->setBreakOnFirstPhpError(false);
	}

	public function testShouldNotBeAffectToReturnValue()
	{
		set_error_handler(function(){});

		$it = $this->it;
		$it->setTestCallback(function() use($it)
		{
			trigger_error('');
			$it->getRunResultsBuffer()->addResult(true);
		});

		$this->assertTrue($it->run());

		restore_error_handler();
	}

	public function testShouldNotBeAddErrorsToStack()
	{
		set_error_handler(function(){});

		$it = $this->it;
		$it->setTestCallback(function() use(&$runResultsBuffer, $it)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			trigger_error('');
		});

		$it->run();

		$this->assertSame(array(), $runResultsBuffer->getResults());

		restore_error_handler();
	}

	public function testShouldNotBeCatchPhpErrorException()
	{
		$it = $this->it;
		$it->setTestCallback(function(){
			throw new \spectrum\core\ExceptionPhpError('foo');
		});

		$this->assertThrowException('\spectrum\core\ExceptionPhpError', 'foo', function() use($it){
			$it->run();
		});
	}

	public function testShouldBeIgnoreBreakOnFirstPhpError()
	{
		set_error_handler(function(){});

		$it = $this->it;
		$it->errorHandling->setBreakOnFirstPhpError(true);
		$it->setTestCallback(function(){
			trigger_error('');
		});

		$this->assertNull($it->run());

		restore_error_handler();
	}
}