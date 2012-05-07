<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;
use spectrum\constructionCommands\Manager;

require_once __DIR__ . '/../../init.php';

class SetDeclaringContainerTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeSetDeclaringContainer()
	{
		$describe = new \spectrum\core\SpecContainerDescribe();
		Manager::setDeclaringContainer($describe);

		$this->assertSame($describe, Manager::getCurrentContainer());
	}

	public function testShouldBeAcceptNull()
	{
		Manager::setDeclaringContainer(new \spectrum\core\SpecContainerDescribe());
		Manager::setDeclaringContainer(null);
		$this->assertSame(\spectrum\RootDescribe::getOnceInstance(), Manager::getCurrentContainer());
	}

	public function testShouldBeAcceptOnlySpecContainerInstances()
	{
		$this->assertThrowException('\Exception', function(){
			Manager::setDeclaringContainer(new \spectrum\core\SpecItemIt());
		});
	}
}