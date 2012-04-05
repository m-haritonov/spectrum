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

class GetDeclaringContainerTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeReturnNullByDefault()
	{
		$this->assertNull(Manager::getDeclaringContainer());
	}

	public function testShouldBeReturnDeclaringContainer()
	{
		$describe = new \spectrum\core\SpecContainerDescribe();
		Manager::setDeclaringContainer($describe);
		$this->assertSame($describe, Manager::getDeclaringContainer());
	}
}