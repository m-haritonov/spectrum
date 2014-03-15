<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\_internal;

use spectrum\core\ContextData;

require_once __DIR__ . '/../init.php';

class CallFunctionOnContextDataTest extends \spectrum\tests\Test
{
	public function testCallsFunctionWithPassedArguments()
	{
		$passedArguments = array();
		\spectrum\_internal\callFunctionOnContextData(function() use(&$passedArguments){
			$passedArguments[] = func_get_args();
		}, array("aaa", "bbb", "ccc"), new ContextData());
	
		$this->assertSame(array(array("aaa", "bbb", "ccc")), $passedArguments);
	}
	
	public function testReturnsFunctionReturnValue()
	{
		$callResults = array();
		$callResults[] = \spectrum\_internal\callFunctionOnContextData(function(){ return "aaa"; }, array(), new ContextData());
		$this->assertSame(array("aaa"), $callResults);
	}
	
	public function testPhpIsGreaterThanOrEqualTo54_BindContextDataToThisVariable()
	{
		if (version_compare(PHP_VERSION, '5.4', '<'))
			return null;
		
		$thisValue = null;
		$contextData = new ContextData();
		\spectrum\_internal\callFunctionOnContextData(function() use(&$thisValue){
			$thisValue = $this;
		}, array(), $contextData);
	
		$this->assertInstanceOf('\spectrum\core\ContextData', $thisValue);
		$this->assertSame($contextData, $thisValue);
	}
}