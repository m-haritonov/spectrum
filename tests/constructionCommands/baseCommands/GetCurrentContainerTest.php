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

class GetCurrentContainerTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testDeclaringState_ShouldBeReturnCurrentContainerIfItSet()
	{
		$container = new \spectrum\core\SpecContainerDescribe();
		Manager::setDeclaringContainer($container);

		$this->assertSame($container, Manager::getCurrentContainer());
	}

	public function testDeclaringState_ShouldBeReturnRootDescribeIfCurrentContainerNotSet()
	{
		$this->assertSame(\spectrum\RootDescribe::getOnceInstance(), Manager::getCurrentContainer());
	}

	public function testDeclaringState_ShouldBeReturnOnceRootDescribeInstance()
	{
		$container1 = Manager::getCurrentContainer();
		$container2 = Manager::getCurrentContainer();
		$this->assertSame($container1, $container2);
	}

/**/

	public function testRunningState_ParentDescribe_ShouldBeReturnNearestParentDescribe()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->It
		');

		$specs[2]->setTestCallback(function() use(&$currentContainer) {
			$currentContainer = Manager::getCurrentContainer();
		});
		$specs[2]->run();

		$this->assertSame($specs[1], $currentContainer);
	}

	public function testRunningState_ParentContext_ShouldBeReturnNearestParentContext()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->It
		');

		$specs[2]->setTestCallback(function() use(&$currentContainer) {
			$currentContainer = Manager::getCurrentContainer();
		});
		$specs[2]->run();

		$this->assertSame($specs[1], $currentContainer);
	}

	public function testRunningState_ShouldBeReturnNullIfHasNoAncestorContainer()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->setTestCallback(function() use(&$isCalled, &$currentContainer) {
			$isCalled = true;
			$currentContainer = Manager::getCurrentContainer();
		});
		$it->run();

		$this->assertTrue($isCalled);
		$this->assertNull($currentContainer);
	}
}