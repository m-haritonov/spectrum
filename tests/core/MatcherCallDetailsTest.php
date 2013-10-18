<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core;

use spectrum\core\MatcherCallDetails;

require_once __DIR__ . '/../init.php';

class MatcherCallDetailsTest extends \spectrum\tests\Test
{
	public function testReturnsSetValues()
	{
		$exception = new \Exception();
		
		$matcherCallDetails = new MatcherCallDetails();
		$matcherCallDetails->setTestedValue('aaa');
		$matcherCallDetails->setNot(true);
		$matcherCallDetails->setResult('bbb');
		$matcherCallDetails->setMatcherName('ccc');
		$matcherCallDetails->setMatcherArguments(array('ddd', 'eee', 'fff'));
		$matcherCallDetails->setMatcherReturnValue('ggg');
		$matcherCallDetails->setMatcherException($exception);
		$matcherCallDetails->setFile('aaa/bbb.php');
		$matcherCallDetails->setLine(238);
		
		$this->assertSame('aaa', $matcherCallDetails->getTestedValue());
		$this->assertSame(true, $matcherCallDetails->getNot());
		$this->assertSame('bbb', $matcherCallDetails->getResult());
		$this->assertSame('ccc', $matcherCallDetails->getMatcherName());
		$this->assertSame(array('ddd', 'eee', 'fff'), $matcherCallDetails->getMatcherArguments());
		$this->assertSame('ggg', $matcherCallDetails->getMatcherReturnValue());
		$this->assertSame($exception, $matcherCallDetails->getMatcherException());
		$this->assertSame('aaa/bbb.php', $matcherCallDetails->getFile());
		$this->assertSame(238, $matcherCallDetails->getLine());
		
		$matcherCallDetails->setNot(false);
		$matcherCallDetails->setMatcherException(null);
		
		$this->assertSame(false, $matcherCallDetails->getNot());
		$this->assertSame(null, $matcherCallDetails->getMatcherException());
	}
	
	public function testReturnsEmptyValuesByDefault()
	{
		$matcherCallDetails = new MatcherCallDetails();
		
		$this->assertSame(null, $matcherCallDetails->getTestedValue());
		$this->assertSame(null, $matcherCallDetails->getNot());
		$this->assertSame(null, $matcherCallDetails->getResult());
		$this->assertSame(null, $matcherCallDetails->getMatcherName());
		$this->assertSame(array(), $matcherCallDetails->getMatcherArguments());
		$this->assertSame(null, $matcherCallDetails->getMatcherReturnValue());
		$this->assertSame(null, $matcherCallDetails->getMatcherException());
		$this->assertSame(null, $matcherCallDetails->getFile());
		$this->assertSame(null, $matcherCallDetails->getLine());
	}
}