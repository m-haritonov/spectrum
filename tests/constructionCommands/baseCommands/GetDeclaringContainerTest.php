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

class GetDeclaringContainerTest extends \spectrum\constructionCommands\commands\Test
{
	public function testShouldBeReturnNullByDefault()
	{
		$this->assertNull(manager::getDeclaringContainer());
	}

	public function testShouldBeReturnDeclaringContainer()
	{
		$describe = new \spectrum\core\SpecContainerDescribe();
		manager::setDeclaringContainer($describe);
		$this->assertSame($describe, manager::getDeclaringContainer());
	}
}