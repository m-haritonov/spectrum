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

class GetCurrentContainerTest extends \spectrum\constructionCommands\commands\Test
{
	public function testDeclaringState_ShouldBeReturnCurrentContainerIfItSet()
	{
		$container = new \spectrum\core\SpecContainerDescribe();
		manager::setDeclaringContainer($container);

		$this->assertSame($container, manager::getCurrentContainer());
	}

	public function testDeclaringState_ShouldBeReturnRootDescribeIfCurrentContainerNotSet()
	{
		$this->assertSame(\spectrum\RootSpec::getOnceInstance(), manager::getCurrentContainer());
	}

	public function testDeclaringState_ShouldBeReturnOnceRootDescribeInstance()
	{
		$container1 = manager::getCurrentContainer();
		$container2 = manager::getCurrentContainer();
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
			$currentContainer = manager::getCurrentContainer();
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
			$currentContainer = manager::getCurrentContainer();
		});
		$specs[2]->run();

		$this->assertSame($specs[1], $currentContainer);
	}

	public function testRunningState_ShouldBeReturnNullIfHasNoAncestorContainer()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->setTestCallback(function() use(&$isCalled, &$currentContainer) {
			$isCalled = true;
			$currentContainer = manager::getCurrentContainer();
		});
		$it->run();

		$this->assertTrue($isCalled);
		$this->assertNull($currentContainer);
	}
}