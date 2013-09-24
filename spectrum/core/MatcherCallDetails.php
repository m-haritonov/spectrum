<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

class MatcherCallDetails implements MatcherCallDetailsInterface
{
	protected $testedValue;
//	protected $testedValueSourceCode;
	protected $not;
	protected $result;
	protected $matcherName;
	protected $matcherArguments = array();
	protected $matcherReturnValue;
	protected $matcherException;

	public function setTestedValue($testedValue){ $this->testedValue = $testedValue; }
	public function getTestedValue(){ return $this->testedValue; }

//	public function setTestedValueSourceCode($sourceCode){ $this->testedValueSourceCode = $sourceCode; }
//	public function getTestedValueSourceCode(){ return $this->testedValueSourceCode; }

	public function setNot($not){ $this->not = $not; }
	public function getNot(){ return $this->not; }

	public function setResult($result){ $this->result = $result; }
	public function getResult(){ return $this->result; }

	public function setMatcherName($matcherName){ $this->matcherName = $matcherName; }
	public function getMatcherName(){ return $this->matcherName; }

	public function setMatcherArguments(array $matcherArguments){ $this->matcherArguments = $matcherArguments; }
	public function getMatcherArguments(){ return $this->matcherArguments; }

	public function setMatcherReturnValue($matcherReturnValue){ $this->matcherReturnValue = $matcherReturnValue; }
	public function getMatcherReturnValue(){ return $this->matcherReturnValue; }
	
	public function setMatcherException(\Exception $exception = null){ $this->matcherException = $exception; }
	public function getMatcherException(){ return $this->matcherException; }
}