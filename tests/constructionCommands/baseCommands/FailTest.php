<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;
use spectrum\constructionCommands\Manager;

require_once dirname(__FILE__) . '/../../init.php';

class FailTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeFailResultToRunResultsBuffer()
	{
		$it = Manager::it('foo', function() use(&$runResultsBuffer){
			Manager::fail('bar baz');
			Manager::fail('foooo', 110);
			$runResultsBuffer = Manager::getCurrentItem()->getRunResultsBuffer();
		});

		$it->run();

		$results = $runResultsBuffer->getResults();

		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\constructionCommands\ExceptionFail);
		$this->assertEquals('bar baz', $results[0]['details']->getMessage());
		$this->assertEquals(0, $results[0]['details']->getCode());

		$this->assertFalse($results[1]['result']);
		$this->assertTrue($results[1]['details'] instanceof \spectrum\constructionCommands\ExceptionFail);
		$this->assertEquals('foooo', $results[1]['details']->getMessage());
		$this->assertEquals(110, $results[1]['details']->getCode());
	}
}