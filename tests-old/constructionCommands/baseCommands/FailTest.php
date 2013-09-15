<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands;
use spectrum\constructionCommands\manager;

require_once __DIR__ . '/../../init.php';

class FailTest extends \spectrum\constructionCommands\commands\Test
{
	public function testShouldBeFailResultToResultBuffer()
	{
		$it = manager::it('foo', function() use(&$resultBuffer){
			manager::fail('bar baz');
			manager::fail('foooo', 110);
			$resultBuffer = manager::getCurrentItem()->getResultBuffer();
		});

		$it->run();

		$results = $resultBuffer->getResults();

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