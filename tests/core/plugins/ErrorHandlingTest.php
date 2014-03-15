<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\core\plugins;

use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class ErrorHandlingTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::setAllowErrorHandlingModify(true);
	}
	
	public function providerSetCatchPhpErrors()
	{
		return array(
			array(true, -1),
			array(false, 0),
			array('', 0),
			array(null, null),
			array(1, 1),
			array(2, 2),
		);
	}
	
	/**
	 * @dataProvider providerSetCatchPhpErrors
	 */
	public function testSetCatchPhpErrors_SetsValue($errorLevel, $resultErrorLevel)
	{
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors($errorLevel);
		$this->assertSame($resultErrorLevel, $spec->errorHandling->getCatchPhpErrors());
	}
	
	public function testSetCatchPhpErrors_CallOnRun_ThrowsExceptionAndDoesNotChangeValue()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->errorHandling->setCatchPhpErrors(0);
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		

		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(1);
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\ErrorHandling::setCatchPhpErrors" method is forbidden on run', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame(1, $spec->errorHandling->getCatchPhpErrors());
	}
	
	public function testSetCatchPhpErrors_ErrorHandlingModifyIsDeniedInConfig_ThrowsExceptionAndDoesNotChangeValue()
	{
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(1);
		
		config::setAllowErrorHandlingModify(false);
		
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Error handling modify deny in config', function() use($spec){
			$spec->errorHandling->setCatchPhpErrors(0);
		});
		
		$this->assertSame(1, $spec->errorHandling->getCatchPhpErrors());
	}
	
/**/
	
	public function testGetCatchPhpErrors_ReturnsSetValue()
	{
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(1);
		$this->assertSame(1, $spec->errorHandling->getCatchPhpErrors());
		
		$spec->errorHandling->setCatchPhpErrors(2);
		$this->assertSame(2, $spec->errorHandling->getCatchPhpErrors());
	}
	
	public function testGetCatchPhpErrors_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->errorHandling->getCatchPhpErrors());
	}
	
/**/
	
	public function testGetCatchPhpErrorsThroughRunningAncestors_ReturnsValueFromRunningAncestorOrFromSelf()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->errorHandling->getCatchPhpErrorsThroughRunningAncestors();
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->Spec(parent3)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2', 'parent2' => 'endingSpec2'));
		
		$specs[0]->errorHandling->setCatchPhpErrors(1);
		$specs['endingSpec1']->errorHandling->setCatchPhpErrors(2);
		$specs['parent1']->errorHandling->setCatchPhpErrors(3);
		$specs['parent2']->errorHandling->setCatchPhpErrors(4);
		
		$specs[0]->run();
		
		$this->assertSame(array(2, 3, 4, 1), \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testGetCatchPhpErrorsThroughRunningAncestors_ReturnsNegativeOneWhenValueIsNotSet()
	{
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(null);
		$this->assertSame(-1, $spec->errorHandling->getCatchPhpErrorsThroughRunningAncestors());
	}
	
/**/
	
	public function testSetBreakOnFirstPhpError_SetsValue()
	{
		$spec = new Spec();
		$spec->errorHandling->setBreakOnFirstPhpError(true);
		$this->assertSame(true, $spec->errorHandling->getBreakOnFirstPhpError());
		
		$spec->errorHandling->setBreakOnFirstPhpError(false);
		$this->assertSame(false, $spec->errorHandling->getBreakOnFirstPhpError());
	}
	
	public function testSetBreakOnFirstPhpError_CallOnRun_ThrowsExceptionAndDoesNotChangeValue()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->errorHandling->setBreakOnFirstPhpError(false);
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		

		$spec = new Spec();
		$spec->errorHandling->setBreakOnFirstPhpError(true);
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\ErrorHandling::setBreakOnFirstPhpError" method is forbidden on run', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame(true, $spec->errorHandling->getBreakOnFirstPhpError());
	}
	
	public function testSetBreakOnFirstPhpError_ErrorHandlingModifyIsDeniedInConfig_ThrowsExceptionAndDoesNotChangeValue()
	{
		$spec = new Spec();
		$spec->errorHandling->setBreakOnFirstPhpError(true);
		
		config::setAllowErrorHandlingModify(false);
		
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Error handling modify deny in config', function() use($spec){
			$spec->errorHandling->setBreakOnFirstPhpError(false);
		});
		
		$this->assertSame(true, $spec->errorHandling->getBreakOnFirstPhpError());
	}
	
/**/
	
	public function testGetBreakOnFirstPhpError_ReturnsSetValue()
	{
		$spec = new Spec();
		$spec->errorHandling->setBreakOnFirstPhpError(true);
		$this->assertSame(true, $spec->errorHandling->getBreakOnFirstPhpError());
		
		$spec->errorHandling->setBreakOnFirstPhpError(false);
		$this->assertSame(false, $spec->errorHandling->getBreakOnFirstPhpError());
	}
	
	public function testGetBreakOnFirstPhpError_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->errorHandling->getBreakOnFirstPhpError());
	}
	
/**/
	
	public function testGetBreakOnFirstPhpErrorThroughRunningAncestors_ReturnsValueFromRunningAncestorOrFromSelf()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->errorHandling->getBreakOnFirstPhpErrorThroughRunningAncestors();
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2'));
		
		$specs[0]->errorHandling->setBreakOnFirstPhpError(true);
		$specs['endingSpec1']->errorHandling->setBreakOnFirstPhpError(false);
		$specs['parent1']->errorHandling->setBreakOnFirstPhpError(false);
		
		$specs[0]->run();
		
		$this->assertSame(array(false, false, true), \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testGetBreakOnFirstPhpErrorThroughRunningAncestors_ReturnsFalseWhenValueIsNotSet()
	{
		$spec = new Spec();
		$spec->errorHandling->setBreakOnFirstPhpError(null);
		$this->assertSame(false, $spec->errorHandling->getBreakOnFirstPhpErrorThroughRunningAncestors());
	}
	
/**/
	
	public function testSetBreakOnFirstMatcherFail_SetsValue()
	{
		$spec = new Spec();
		$spec->errorHandling->setBreakOnFirstMatcherFail(true);
		$this->assertSame(true, $spec->errorHandling->getBreakOnFirstMatcherFail());
		
		$spec->errorHandling->setBreakOnFirstMatcherFail(false);
		$this->assertSame(false, $spec->errorHandling->getBreakOnFirstMatcherFail());
	}
	
	public function testSetBreakOnFirstMatcherFail_CallOnRun_ThrowsExceptionAndDoesNotChangeValue()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->errorHandling->setBreakOnFirstMatcherFail(false);
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		

		$spec = new Spec();
		$spec->errorHandling->setBreakOnFirstMatcherFail(true);
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\ErrorHandling::setBreakOnFirstMatcherFail" method is forbidden on run', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame(true, $spec->errorHandling->getBreakOnFirstMatcherFail());
	}
	
	public function testSetBreakOnFirstMatcherFail_ErrorHandlingModifyIsDeniedInConfig_ThrowsExceptionAndDoesNotChangeValue()
	{
		$spec = new Spec();
		$spec->errorHandling->setBreakOnFirstMatcherFail(true);
		
		config::setAllowErrorHandlingModify(false);
		
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Error handling modify deny in config', function() use($spec){
			$spec->errorHandling->setBreakOnFirstMatcherFail(false);
		});
		
		$this->assertSame(true, $spec->errorHandling->getBreakOnFirstMatcherFail());
	}
	
/**/
	
	public function testGetBreakOnFirstMatcherFail_ReturnsSetValue()
	{
		$spec = new Spec();
		$spec->errorHandling->setBreakOnFirstMatcherFail(true);
		$this->assertSame(true, $spec->errorHandling->getBreakOnFirstMatcherFail());
		
		$spec->errorHandling->setBreakOnFirstMatcherFail(false);
		$this->assertSame(false, $spec->errorHandling->getBreakOnFirstMatcherFail());
	}
	
	public function testGetBreakOnFirstMatcherFail_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->errorHandling->getBreakOnFirstMatcherFail());
	}
	
/**/
	
	public function testGetBreakOnFirstMatcherFailThroughRunningAncestors_ReturnsValueFromRunningAncestorOrFromSelf()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->errorHandling->getBreakOnFirstMatcherFailThroughRunningAncestors();
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2'));
		
		$specs[0]->errorHandling->setBreakOnFirstMatcherFail(true);
		$specs['endingSpec1']->errorHandling->setBreakOnFirstMatcherFail(false);
		$specs['parent1']->errorHandling->setBreakOnFirstMatcherFail(false);
		
		$specs[0]->run();
		
		$this->assertSame(array(false, false, true), \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testGetBreakOnFirstMatcherFailThroughRunningAncestors_ReturnsFalseWhenValueIsNotSet()
	{
		$spec = new Spec();
		$spec->errorHandling->setBreakOnFirstMatcherFail(null);
		$this->assertSame(false, $spec->errorHandling->getBreakOnFirstMatcherFailThroughRunningAncestors());
	}
	
/**/
	
	public function testErrorHandling_GetsPhpErrorDetailsClassFromConfig()
	{
		$phpErrorDetailsClassName = $this->createClass('class ... extends \spectrum\core\details\PhpError {}');
		config::setPhpErrorDetailsClass($phpErrorDetailsClassName);

		error_reporting(E_USER_WARNING);
		
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
			trigger_error("aaa", E_USER_NOTICE);
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(-1);
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf($phpErrorDetailsClassName, $results[0]['details']);
		$this->assertSame('aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_USER_NOTICE, $results[0]['details']->getErrorLevel());
	}
	
	public function testErrorHandling_GetsErrorTypeFromAncestorsOrSelf()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
			trigger_error("aaa", E_USER_NOTICE);
			trigger_error("bbb", E_USER_WARNING);
			trigger_error("ccc", E_USER_ERROR);
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
			->->Spec
		', array(2 => 4));
		
		$specs[1]->errorHandling->setCatchPhpErrors(E_USER_NOTICE);
		$specs[2]->errorHandling->setCatchPhpErrors(E_USER_WARNING);
		$specs[3]->errorHandling->setCatchPhpErrors(E_USER_ERROR);
		$specs[0]->run();
		
		$this->assertSame(3, count(\spectrum\tests\Test::$temp["resultBuffers"]));

		$results = \spectrum\tests\Test::$temp["resultBuffers"][0]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(E_USER_NOTICE, $results[0]['details']->getErrorLevel());
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][1]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(E_USER_WARNING, $results[0]['details']->getErrorLevel());
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][2]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(E_USER_ERROR, $results[0]['details']->getErrorLevel());
	}
	
	public function testErrorHandling_TakesInAccountDefinedOnRunErrorReportingValue()
	{
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
			error_reporting(E_USER_WARNING);
			trigger_error("aaa", E_USER_NOTICE);
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(-1);
		$spec->run();
		
		$this->assertSame(array(), \spectrum\tests\Test::$temp["resultBuffer"]->getResults());
	}
	
	public function testErrorHandling_DoesNotTakeInAccountDefinedBeforeRunErrorReportingValue()
	{
		error_reporting(E_USER_WARNING);
		
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
			trigger_error("aaa", E_USER_NOTICE);
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(-1);
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_USER_NOTICE, $results[0]['details']->getErrorLevel());
	}
	
	public function testErrorHandling_RestoreErrorReportingValueAfterRun()
	{
		error_reporting(E_NOTICE);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(E_NOTICE, error_reporting());
	}
	
	public function testErrorHandling_RemovesErrorHandlerAfterRun()
	{
		$errorHandler = function($errorSeverity, $errorMessage){};
		set_error_handler($errorHandler);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame($errorHandler, $this->getLastErrorHandler());
		
		restore_error_handler();
	}
	
	public function testErrorHandling_RemovesAlienErrorHandlersAddedOnExecute()
	{
		$errorHandler = function($errorSeverity, $errorMessage){};
		set_error_handler($errorHandler);
		
		$this->registerPluginWithCodeInEvent('
			set_error_handler(function($errorSeverity, $errorMessage){});
			set_error_handler(function($errorSeverity, $errorMessage){});
			set_error_handler(function($errorSeverity, $errorMessage){});
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame($errorHandler, $this->getLastErrorHandler());
		
		restore_error_handler();
	}
	
	public function testErrorHandling_CatchesPhpErrorsFromContextsPlugin()
	{
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(-1);
		$spec->contexts->add(function(){ trigger_error("aaa", E_USER_NOTICE); }, 'before');
		$spec->contexts->add(function(){ trigger_error("bbb", E_USER_WARNING); }, 'after');
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(2, count($results));
		
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_USER_NOTICE, $results[0]['details']->getErrorLevel());
		
		$this->assertSame(false, $results[1]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[1]['details']);
		$this->assertSame('bbb', $results[1]['details']->getErrorMessage());
		$this->assertSame(E_USER_WARNING, $results[1]['details']->getErrorLevel());
	}
	
	public function testErrorHandling_CatchesPhpErrorsFromTestPlugin()
	{
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(-1);
		$spec->test->setFunction(function(){ trigger_error("aaa", E_USER_NOTICE); });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_USER_NOTICE, $results[0]['details']->getErrorLevel());
	}
	
	public function testErrorHandling_ErrorHandlerWasRemovedOnExecute_AddsFalseToResultBufferAndDoesNotRemoveOtherErrorHandlers()
	{
		$errorHandler1 = function($errorSeverity, $errorMessage){};
		set_error_handler($errorHandler1);
		
		$errorHandler2 = function($errorSeverity, $errorMessage){};
		set_error_handler($errorHandler2);
		
		$errorHandler3 = function($errorSeverity, $errorMessage){};
		set_error_handler($errorHandler3);
		
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
			restore_error_handler();
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->setName('aaa');
		$spec->run();
		
		$this->assertSame(array(
			array('result' => false, 'details' => 'Error handler in spec "aaa" was removed'),
		), \spectrum\tests\Test::$temp["resultBuffer"]->getResults());
		
		$this->assertSame($errorHandler3, $this->getLastErrorHandler());
		restore_error_handler();
		
		$this->assertSame($errorHandler2, $this->getLastErrorHandler());
		restore_error_handler();
		
		$this->assertSame($errorHandler1, $this->getLastErrorHandler());
		restore_error_handler();
	}
		
	public function testErrorHandling_ErrorTypeIsIncludeTriggeredErrorType_CatchesPhpErrorsAndAddsFalseResultToResultBuffer()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
			trim($aaa);
			trigger_error("bbb", E_USER_WARNING);
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
			->Spec
			->->Spec
		', array(1 => 5, 2 => 5, 3 => 5));
		
		$specs[1]->errorHandling->setCatchPhpErrors(E_NOTICE);
		$specs[2]->errorHandling->setCatchPhpErrors(E_USER_WARNING);
		$specs[3]->errorHandling->setCatchPhpErrors(E_ALL);
		$specs[4]->errorHandling->setCatchPhpErrors(-1);
		$specs[0]->run();
		
		$this->assertSame(4, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][0]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('Undefined variable: aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_NOTICE, $results[0]['details']->getErrorLevel());
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][1]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('bbb', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_USER_WARNING, $results[0]['details']->getErrorLevel());
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][2]->getResults();
		$this->assertSame(2, count($results));
		
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('Undefined variable: aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_NOTICE, $results[0]['details']->getErrorLevel());
		
		$this->assertSame(false, $results[1]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[1]['details']);
		$this->assertSame('bbb', $results[1]['details']->getErrorMessage());
		$this->assertSame(E_USER_WARNING, $results[1]['details']->getErrorLevel());
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][3]->getResults();
		$this->assertSame(2, count($results));
		
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('Undefined variable: aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_NOTICE, $results[0]['details']->getErrorLevel());
		
		$this->assertSame(false, $results[1]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[1]['details']);
		$this->assertSame('bbb', $results[1]['details']->getErrorMessage());
		$this->assertSame(E_USER_WARNING, $results[1]['details']->getErrorLevel());
	}
	
	public function testErrorHandling_ErrorTypeIsNotIncludeTriggeredErrorType_CatchesPhpErrorsAndDoesNotAddResultsToResultBuffer()
	{
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
			trigger_error("aaa", E_USER_WARNING);
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(0);
		$spec->run();
		
		$this->assertSame(array(), \spectrum\tests\Test::$temp["resultBuffer"]->getResults());
	}
	
	public function testErrorHandling_ExpressionWithErrorControlOperator_CatchesPhpErrorsAndDoesNotAddResultsToResultBuffer()
	{
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
			@trim($aaa);
			@trigger_error("aaa");
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(-1);
		$spec->run();
		
		$this->assertSame(array(), \spectrum\tests\Test::$temp["resultBuffer"]->getResults());
	}
	
	public function testErrorHandling_BreakOnFirstPhpErrorIsEnabled_BreaksExecutionOnFirstPhpError()
	{
		\spectrum\tests\Test::$temp["isExecuted"] = false;
		
		$this->registerPluginWithCodeInEvent('
			trigger_error("aaa");
			\spectrum\tests\Test::$temp["isExecuted"] = true;
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->errorHandling->setBreakOnFirstPhpError(true);
		$spec->errorHandling->setCatchPhpErrors(-1);
		$spec->run();
		
		$this->assertSame(false, \spectrum\tests\Test::$temp["isExecuted"]);
	}
	
	public function testErrorHandling_BreakOnFirstPhpErrorIsEnabled_GetsValueFromAncestorsOrSelf()
	{
		\spectrum\tests\Test::$temp["callCount"] = -1;
		\spectrum\tests\Test::$temp["isExecuted"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["callCount"]++;
			
			\spectrum\tests\Test::$temp["isExecuted"][\spectrum\tests\Test::$temp["callCount"]][] = 1;
			trigger_error("aaa");
			\spectrum\tests\Test::$temp["isExecuted"][\spectrum\tests\Test::$temp["callCount"]][] = 2;
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
			->Spec
			->->Spec
		', array(3 => 5));
		
		$specs[0]->errorHandling->setCatchPhpErrors(-1);
		$specs[1]->errorHandling->setBreakOnFirstPhpError(true);
		$specs[2]->errorHandling->setBreakOnFirstPhpError(false);
		$specs[3]->errorHandling->setBreakOnFirstPhpError(true);
		$specs[4]->errorHandling->setBreakOnFirstPhpError(false);
		$specs[0]->run();
		
		$this->assertSame(array(
			array(1),
			array(1, 2),
			array(1),
			array(1, 2),
		), \spectrum\tests\Test::$temp["isExecuted"]);
	}
	
	public function testErrorHandling_BreakOnFirstMatcherFailIsEnabled_BreaksExecutionOnMatcherFail()
	{
		\spectrum\tests\Test::$temp["isExecuted"] = false;
		
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assert($this->getOwnerSpec(), "aaa");
			$assert->failMatcher();
			\spectrum\tests\Test::$temp["isExecuted"] = true;
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(0);
		$spec->matchers->add('failMatcher', function(){ return false; });
		$spec->errorHandling->setBreakOnFirstMatcherFail(true);
		$spec->run();
		
		$this->assertSame(false, \spectrum\tests\Test::$temp["isExecuted"]);
	}
	
	public function testErrorHandling_BreakOnFirstMatcherFailIsEnabled_DoesNotBreakExecutionOnMatcherSuccess()
	{
		\spectrum\tests\Test::$temp["isExecuted"] = false;
		
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assert($this->getOwnerSpec(), "aaa");
			$assert->successMatcher();
			\spectrum\tests\Test::$temp["isExecuted"] = true;
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->errorHandling->setCatchPhpErrors(0);
		$spec->matchers->add('successMatcher', function(){ return true; });
		$spec->errorHandling->setBreakOnFirstMatcherFail(true);
		$spec->run();
		
		$this->assertSame(true, \spectrum\tests\Test::$temp["isExecuted"]);
	}
	
	public function testErrorHandling_BreakOnFirstMatcherFailIsEnabled_GetsValueFromAncestorsOrSelf()
	{
		\spectrum\tests\Test::$temp["callCount"] = -1;
		\spectrum\tests\Test::$temp["isExecuted"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["callCount"]++;
			
			\spectrum\tests\Test::$temp["isExecuted"][\spectrum\tests\Test::$temp["callCount"]][] = 1;
			$assert = new \spectrum\core\Assert($this->getOwnerSpec(), "aaa");
			$assert->failMatcher();
			\spectrum\tests\Test::$temp["isExecuted"][\spectrum\tests\Test::$temp["callCount"]][] = 2;
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
			->Spec
			->->Spec
		', array(3 => 5));
		
		$specs[0]->errorHandling->setCatchPhpErrors(0);
		$specs[0]->matchers->add('failMatcher', function(){ return false; });
		
		$specs[1]->errorHandling->setBreakOnFirstMatcherFail(true);
		$specs[2]->errorHandling->setBreakOnFirstMatcherFail(false);
		$specs[3]->errorHandling->setBreakOnFirstMatcherFail(true);
		$specs[4]->errorHandling->setBreakOnFirstMatcherFail(false);
		
		$specs[0]->run();
		
		$this->assertSame(array(
			array(1),
			array(1, 2),
			array(1),
			array(1, 2),
		), \spectrum\tests\Test::$temp["isExecuted"]);
	}
	
/**/
	
	protected function getLastErrorHandler()
	{
		$lastErrorHandler = set_error_handler(function($errorSeverity, $errorMessage){});
		restore_error_handler();
		return $lastErrorHandler;
	}
}