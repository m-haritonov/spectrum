<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\specItemIt;
use spectrum\core\SpecItemIt;
use spectrum\core\DataInterface;

require_once __DIR__ . '/../../init.php';

class WorldTest extends Test
{
	public function testShouldBeBindWorldToThisVariable()
	{
		if (version_compare(PHP_VERSION, '5.4', '<'))
			return;
		
		$it = new SpecItemIt();
		$it->builders->add(function(){
			$this->foo = 123;
		});

		$it->destroyers->add(function(){
			$this->bar = 456;
		});

		$it->setTestCallback(function() use(&$thisVar){
			$this->baz = 789;
			$thisVar = $this;
		});

		$it->run();

		$this->assertTrue($thisVar instanceof DataInterface);
		$this->assertEquals(123, $thisVar->foo);
		$this->assertEquals(456, $thisVar->bar);
		$this->assertEquals(789, $thisVar->baz);
	}

	public function testShouldBeAvailableWorldThroughRegistry()
	{
		$it = new SpecItemIt();
		$it->builders->add(function(){
			\spectrum\core\Registry::getWorld()->foo = 123;
		});

		$it->destroyers->add(function(){
			\spectrum\core\Registry::getWorld()->bar = 456;
		});

		$it->setTestCallback(function() use(&$world){
			\spectrum\core\Registry::getWorld()->baz = 789;
			$world = \spectrum\core\Registry::getWorld();
		});

		$it->run();

		$this->assertTrue($world instanceof DataInterface);
		$this->assertEquals(123, $world->foo);
		$this->assertEquals(456, $world->bar);
		$this->assertEquals(789, $world->baz);
	}

	public function testShouldBeCreateNewWorldForEveryRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use(&$worlds){
			$worlds[] = \spectrum\core\Registry::getWorld();
		});

		$it->run();
		$it->run();

		$this->assertTrue($worlds[0] instanceof DataInterface);
		$this->assertTrue($worlds[1] instanceof DataInterface);
		$this->assertNotSame($worlds[0], $worlds[1]);
	}

	public function testShouldBeUseBuildersPluginsForBuildWorld()
	{
		$it = new SpecItemIt();
		$it->builders->add(function(){
			\spectrum\core\Registry::getWorld()->foo = 'bar';
		});

		$it->setTestCallback(function() use(&$isApplyBeforeRun){
			$isApplyBeforeRun = (\spectrum\core\Registry::getWorld()->foo == 'bar');
		});

		$it->run();

		$this->assertTrue($isApplyBeforeRun);
	}

	public function testShouldNotBeUseBuildersPluginsForDestroyWorld()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function(){
			\spectrum\core\Registry::getWorld()->foo = 'bar';
		});

		$it->builders->add(function() use(&$isApplyAfterRun){
			$isApplyAfterRun = (@\spectrum\core\Registry::getWorld()->foo == 'bar');
		});

		$it->run();

		$this->assertFalse($isApplyAfterRun);
	}

	public function testShouldBeUseDestroyersPluginsForDestroyWorld()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function(){
			\spectrum\core\Registry::getWorld()->foo = 'bar';
		});

		$it->destroyers->add(function() use(&$isApplyAfterRun){
			$isApplyAfterRun = (\spectrum\core\Registry::getWorld()->foo == 'bar');
		});

		$it->run();

		$this->assertTrue($isApplyAfterRun);
	}

	public function testShouldNotBeUseDestroyersPluginsForBuildWorld()
	{
		$it = new SpecItemIt();
		$it->destroyers->add(function(){
			\spectrum\core\Registry::getWorld()->foo = 'bar';
		});

		$it->setTestCallback(function() use(&$isApplyBeforeRun){
			$isApplyBeforeRun = (@\spectrum\core\Registry::getWorld()->foo == 'bar');
		});

		$it->run();

		$this->assertFalse($isApplyBeforeRun);
	}

	public function testShouldBeApplyBuildersAndDestroyersToSharedWorld()
	{
		$it = new SpecItemIt();
		$it->builders->add(function() use(&$worlds, $it){
			$worlds['inBuilder'] = \spectrum\core\Registry::getWorld();
		});

		$it->setTestCallback(function() use(&$worlds, $it){
			$worlds['inTest'] = \spectrum\core\Registry::getWorld();
		});

		$it->destroyers->add(function() use(&$worlds, $it){
			$worlds['inDestroyer'] = \spectrum\core\Registry::getWorld();
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

		$specs[0]->builders->add(function(){ \spectrum\core\Registry::getWorld()->callOrder[] = 0; });
		$specs[1]->builders->add(function(){ \spectrum\core\Registry::getWorld()->callOrder[] = 1; });
		$specs[2]->builders->add(function(){ \spectrum\core\Registry::getWorld()->callOrder[] = 2; });
		$specs[3]->builders->add(function(){ \spectrum\core\Registry::getWorld()->callOrder[] = 3; });
		$specs[4]->builders->add(function(){ \spectrum\core\Registry::getWorld()->callOrder[] = 4; });
		$specs[5]->builders->add(function(){ \spectrum\core\Registry::getWorld()->callOrder[] = 5; });
		$specs[6]->builders->add(function(){ \spectrum\core\Registry::getWorld()->callOrder[] = 6; });

		$specs[6]->setTestCallback(function() use(&$resultCallOrder){
			$resultCallOrder = \spectrum\core\Registry::getWorld()->callOrder;
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