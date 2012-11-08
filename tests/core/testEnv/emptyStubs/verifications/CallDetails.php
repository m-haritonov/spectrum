<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv\emptyStubs\verifications;

class CallDetails implements \spectrum\core\verifications\CallDetailsInterface
{
	public function __construct(){}

/**/
	
	public function setValue1($value){}
	public function getValue1(){}
	
/**/

	public function setValue1SourceCode($sourceCode){}
	public function getValue1SourceCode(){}

/**/

	public function setOperator($operator){}
	public function getOperator(){}

/**/
	
	public function setValue2($value){}
	public function getValue2(){}
	
/**/

	public function setValue2SourceCode($sourceCode){}
	public function getValue2SourceCode(){}

/**/

	public function setVerifyFunctionName($verifyFunctionName){}
	public function getVerifyFunctionName(){}
	
/**/
	
	public function setResult($result){}
	public function getResult(){}
}