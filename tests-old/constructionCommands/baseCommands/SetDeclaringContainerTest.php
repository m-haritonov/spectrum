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

class SetDeclaringContainerTest extends \spectrum\constructionCommands\commands\Test
{
	public function testShouldBeSetDeclaringContainer()
	{
		$describe = new \spectrum\core\SpecContainerDescribe();
		manager::setDeclaringContainer($describe);

		$this->assertSame($describe, manager::getCurrentContainer());
	}

	public function testShouldBeAcceptNull()
	{
		manager::setDeclaringContainer(new \spectrum\core\SpecContainerDescribe());
		manager::setDeclaringContainer(null);
		$this->assertSame(\spectrum\RootSpec::getOnceInstance(), manager::getCurrentContainer());
	}

	public function testShouldBeAcceptOnlySpecContainerInstances()
	{
		$this->assertThrowException('\Exception', function(){
			manager::setDeclaringContainer(new \spectrum\core\SpecItemIt());
		});
	}
}