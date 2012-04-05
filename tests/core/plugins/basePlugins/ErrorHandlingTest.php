<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;
require_once dirname(__FILE__) . '/../../../init.php';

use spectrum\core\Config;

class ErrorHandlingTest extends Test
{
	public function testSetCatchExceptions_ShouldBeThrowExceptionIfNotAllowErrorHandlingModify()
	{
		Config::setAllowErrorHandlingModify(false);
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Error handling modify deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->errorHandling->setCatchExceptions(false);
		});
	}

	public function testSetCatchPhpErrors_ShouldBeThrowExceptionIfNotAllowErrorHandlingModify()
	{
		Config::setAllowErrorHandlingModify(false);
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Error handling modify deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->errorHandling->setCatchPhpErrors(false);
		});
	}

	public function testSetBreakOnFirstPhpError_ShouldBeThrowExceptionIfNotAllowErrorHandlingModify()
	{
		Config::setAllowErrorHandlingModify(false);
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Error handling modify deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->errorHandling->setBreakOnFirstPhpError(false);
		});
	}

	public function testSetBreakOnFirstMatcherFail_ShouldBeThrowExceptionIfNotAllowErrorHandlingModify()
	{
		Config::setAllowErrorHandlingModify(false);
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Error handling modify deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->errorHandling->setBreakOnFirstMatcherFail(false);
		});
	}
}