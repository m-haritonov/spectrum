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

namespace spectrum\core\plugins\basePlugins;
require_once dirname(__FILE__) . '/../../../init.php';

use spectrum\core\Config;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
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