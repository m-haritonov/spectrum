<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\testHelpers\emptyStubs\core\asserts;

/**
 * @property not
 */
class CallDetails implements \spectrum\core\asserts\CallDetailsInterface
{
	public function setTestedValue($actualValue){}
	public function getTestedValue(){}
	public function setNot($not){}
	public function getNot(){}
	public function setMatcherName($matcherName){}
	public function getMatcherName(){}
	public function setMatcherArguments(array $matcherArgs){}
	public function getMatcherArguments(){}
	public function setMatcherReturnValue($matcherReturnValue){}
	public function getMatcherReturnValue(){}
}