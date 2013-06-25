<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\asserts;

interface CallDetailsInterface
{
	public function setTestedValue($actualValue);
	public function getTestedValue();
	
//	public function setTestedValueSourceCode($sourceCode);
//	public function getTestedValueSourceCode();

	public function setNot($not);
	public function getNot();
	
	public function setMatcherName($matcherName);
	public function getMatcherName();
	
	public function setMatcherArguments(array $matcherArgs);
	public function getMatcherArguments();
	
	public function setMatcherReturnValue($matcherReturnValue);
	public function getMatcherReturnValue();
}