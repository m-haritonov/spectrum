<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

interface MatcherCallDetailsInterface
{
	public function setTestedValue($testedValue);
	public function getTestedValue();
	
//	public function setTestedValueSourceCode($sourceCode);
//	public function getTestedValueSourceCode();

	public function setNot($not);
	public function getNot();
	
	public function setResult($result);
	public function getResult();
	
	public function setMatcherName($matcherName);
	public function getMatcherName();
	
	public function setMatcherArguments(array $matcherArguments);
	public function getMatcherArguments();
	
	public function setMatcherReturnValue($matcherReturnValue);
	public function getMatcherReturnValue();
	
	public function setMatcherException(\Exception $exception = null);
	public function getMatcherException();
}