<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands\internal;

use spectrum\constructionCommands\callBroker;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../init.php';

class CallFunctionOnDeclaringSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtDeclaringState_CallsPassedFunctionOnPassedDeclaringSpec()
	{
		$spec = new Spec();
		$declaringSpec = null;
		callBroker::internal_callFunctionOnDeclaringSpec(function() use(&$declaringSpec){
			$declaringSpec = callBroker::internal_getDeclaringSpec();
		}, $spec);
		
		$this->assertSame($spec, $declaringSpec);
	}
	
	public function testCallsAtDeclaringState_RestoresDeclaringSpecAfterCall()
	{
		$spec = new Spec();
		callBroker::internal_setDeclaringSpec($spec);
		callBroker::internal_callFunctionOnDeclaringSpec(function(){}, new Spec());
		
		$this->assertSame($spec, callBroker::internal_getDeclaringSpec());
	}
	
	public function testCallsAtDeclaringState_DoesNotPassArgumentsToCalleeFunction()
	{
		$passedArguments = null;
		callBroker::internal_callFunctionOnDeclaringSpec(function() use(&$passedArguments){
			$passedArguments = func_get_args();
		}, new Spec());
		
		$this->assertSame(array(), $passedArguments);
	}

	public function testCallsAtDeclaringState_ReturnsReturnValueOfPassedFunction()
	{
		$this->assertSame('aaa', callBroker::internal_callFunctionOnDeclaringSpec(function(){
			return 'aaa';
		}, new Spec()));
	}
}