<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specItemIt;
use spectrum\core\SpecItemIt;
use spectrum\core\World;

require_once dirname(__FILE__) . '/../../init.php';

class WorldCreationTest extends Test
{
	public function testShouldBeCreateNewWorldForEveryRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function($world) use(&$worlds, $it){
			$worlds[] = $world;
		});

		$it->run();
		$it->run();

		$this->assertTrue($worlds[0] instanceof World);
		$this->assertTrue($worlds[1] instanceof World);
		$this->assertNotSame($worlds[0], $worlds[1]);
	}

	public function testShouldBeUseBuildersPluginsForBuildWorld()
	{
		$it = new SpecItemIt();
		$it->builders->add(function($world){
			$world->foo = 'bar';
		});

		$it->setTestCallback(function($world) use(&$isApplyBeforeRun){
			$isApplyBeforeRun = ($world->foo == 'bar');
		});

		$it->run();

		$this->assertTrue($isApplyBeforeRun);
	}

	public function testShouldNotBeUseBuildersPluginsForDestroyWorld()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function($world){
			$world->foo = 'bar';
		});

		$it->builders->add(function($world) use(&$isApplyAfterRun){
			$isApplyAfterRun = (@$world->foo == 'bar');
		});

		$it->run();

		$this->assertFalse($isApplyAfterRun);
	}

	public function testShouldBeUseDestroyersPluginsForDestroyWorld()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function($world){
			$world->foo = 'bar';
		});

		$it->destroyers->add(function($world) use(&$isApplyAfterRun){
			$isApplyAfterRun = ($world->foo == 'bar');
		});

		$it->run();

		$this->assertTrue($isApplyAfterRun);
	}

	public function testShouldNotBeUseDestroyersPluginsForBuildWorld()
	{
		$it = new SpecItemIt();
		$it->destroyers->add(function($world){
			$world->foo = 'bar';
		});

		$it->setTestCallback(function($world) use(&$isApplyBeforeRun){
			$isApplyBeforeRun = (@$world->foo == 'bar');
		});

		$it->run();

		$this->assertFalse($isApplyBeforeRun);
	}

	public function testShouldBeApplyBuildersAndDestroyersToSharedWorld()
	{
		$it = new SpecItemIt();
		$it->builders->add(function($world) use(&$worlds, $it){
			$worlds['inBuilder'] = $world;
		});

		$it->setTestCallback(function($world) use(&$worlds, $it){
			$worlds['inTest'] = $world;
		});

		$it->destroyers->add(function($world) use(&$worlds, $it){
			$worlds['inDestroyer'] = $world;
		});

		$it->run();

		$this->assertSame($worlds['inBuilder'], $worlds['inTest']);
		$this->assertSame($worlds['inTest'], $worlds['inDestroyer']);
	}


	public function testShouldBeApplyAllBuildersFromAncestorsAndAncestorRunningContexts()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context
			->Describe
			->->Context
			->->->Context
			->->It
		');

		$specs[0]->builders->add(function($world){ $world->callOrder[] = 0; });
		$specs[1]->builders->add(function($world){ $world->callOrder[] = 1; });
		$specs[2]->builders->add(function($world){ $world->callOrder[] = 2; });
		$specs[3]->builders->add(function($world){ $world->callOrder[] = 3; });
		$specs[4]->builders->add(function($world){ $world->callOrder[] = 4; });
		$specs[5]->builders->add(function($world){ $world->callOrder[] = 5; });
		$specs[6]->builders->add(function($world){ $world->callOrder[] = 6; });

		$specs[6]->setTestCallback(function($world) use(&$resultCallOrder){
			$resultCallOrder = $world->callOrder;
		});

		$specs[0]->run();

		$this->assertSame(array(0, 1, 2, 3, 4, 5, 6), $resultCallOrder);
	}

	public function testShouldBeApplyAllDestroyersFromAncestorsAndAncestorRunningContexts()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context
			->Describe
			->->Context
			->->->Context
			->->It
		');

		$specs[0]->destroyers->add(function() use(&$callOrder){ $callOrder[] = 0; });
		$specs[1]->destroyers->add(function() use(&$callOrder){ $callOrder[] = 1; });
		$specs[2]->destroyers->add(function() use(&$callOrder){ $callOrder[] = 2; });
		$specs[3]->destroyers->add(function() use(&$callOrder){ $callOrder[] = 3; });
		$specs[4]->destroyers->add(function() use(&$callOrder){ $callOrder[] = 4; });
		$specs[5]->destroyers->add(function() use(&$callOrder){ $callOrder[] = 5; });
		$specs[6]->destroyers->add(function() use(&$callOrder){ $callOrder[] = 6; });

		$specs[6]->setTestCallback(function(){});
		$specs[0]->run();

		$this->assertSame(array(6, 5, 4, 3, 2, 1, 0), $callOrder);
	}
}