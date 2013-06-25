<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\asserts;

class CallDetails implements CallDetailsInterface
{
	protected $testedValue;
//	protected $testedValueSourceCode;
	protected $not;
	protected $matcherName;
	protected $matcherArguments = array();
	protected $matcherReturnValue;

	public function setTestedValue($value){ $this->testedValue = $value; }
	public function getTestedValue(){ return $this->testedValue; }

/**/

//	public function setTestedValueSourceCode($sourceCode){ $this->testedValueSourceCode = $sourceCode; }
//	public function getTestedValueSourceCode(){ return $this->testedValueSourceCode; }

/**/

	public function setNot($not){ $this->not = $not; }
	public function getNot(){ return $this->not; }

/**/

	public function setMatcherName($matcherName){ $this->matcherName = $matcherName; }
	public function getMatcherName(){ return $this->matcherName; }

/**/

	public function setMatcherArguments(array $matcherArgs){ $this->matcherArguments = $matcherArgs; }
	public function getMatcherArguments(){ return $this->matcherArguments; }

/**/

	public function setMatcherReturnValue($matcherReturnValue){ $this->matcherReturnValue = $matcherReturnValue; }
	public function getMatcherReturnValue(){ return $this->matcherReturnValue; }
}