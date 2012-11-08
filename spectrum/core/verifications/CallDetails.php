<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\verifications;

class CallDetails implements CallDetailsInterface
{
	protected $value1;
	protected $value1SourceCode;
	protected $operator;
	protected $value2;
	protected $value2SourceCode;
	protected $verifyFunctionName;
	protected $result;

	public function __construct()
	{
	}

/**/

	public function setValue1($value){ $this->value1 = $value; }
	public function getValue1(){ return $this->value1; }
	
/**/

	public function setValue1SourceCode($sourceCode){ $this->value1SourceCode = $sourceCode; }
	public function getValue1SourceCode(){ return $this->value1SourceCode; }

/**/

	public function setOperator($operator){ $this->operator = $operator; }
	public function getOperator(){ return $this->operator; }

/**/

	public function setValue2($value){ $this->value2 = $value; }
	public function getValue2(){ return $this->value2; }
	
/**/

	public function setValue2SourceCode($sourceCode){ $this->value2SourceCode = $sourceCode; }
	public function getValue2SourceCode(){ return $this->value2SourceCode; }
	
/**/

	public function setVerifyFunctionName($verifyFunctionName){ $this->verifyFunctionName = $verifyFunctionName; }
	public function getVerifyFunctionName(){ return $this->verifyFunctionName; }	
	
/**/

	public function setResult($result){ $this->result = $result; }
	public function getResult(){ return $this->result; }
}