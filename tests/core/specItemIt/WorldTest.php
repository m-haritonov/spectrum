<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specItemIt;
use spectrum\core\SpecItemIt;
use spectrum\core\WorldInterface;

require_once __DIR__ . '/../../init.php';

class WorldTest extends Test
{
	public function testShouldBeBindWorldToThisVariable()
	{
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

		$this->assertTrue($thisVar instanceof WorldInterface);
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

		$this->assertTrue($world instanceof WorldInterface);
		$this->assertEquals(123, $world->foo);
		$this->assertEquals(456, $world->bar);
		$this->assertEquals(789, $world->baz);
	}

	public function testShouldBeCreateNewWorldForEveryRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use(&$worlds){
			$worlds[] = $this;
		});

		$it->run();
		$it->run();

		$this->assertTrue($worlds[0] instanceof WorldInterface);
		$this->assertTrue($worlds[1] instanceof WorldInterface);
		$this->assertNotSame($worlds[0], $worlds[1]);
	}

	public function testShouldBeUseBuildersPluginsForBuildWorld()
	{
		$it = new SpecItemIt();
		$it->builders->add(function(){
			$this->foo = 'bar';
		});

		$it->setTestCallback(function() use(&$isApplyBeforeRun){
			$isApplyBeforeRun = ($this->foo == 'bar');
		});

		$it->run();

		$this->assertTrue($isApplyBeforeRun);
	}

	public function testShouldNotBeUseBuildersPluginsForDestroyWorld()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function(){
			$this->foo = 'bar';
		});

		$it->builders->add(function() use(&$isApplyAfterRun){
			$isApplyAfterRun = (@$this->foo == 'bar');
		});

		$it->run();

		$this->assertFalse($isApplyAfterRun);
	}

	public function testShouldBeUseDestroyersPluginsForDestroyWorld()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function(){
			$this->foo = 'bar';
		});

		$it->destroyers->add(function() use(&$isApplyAfterRun){
			$isApplyAfterRun = ($this->foo == 'bar');
		});

		$it->run();

		$this->assertTrue($isApplyAfterRun);
	}

	public function testShouldNotBeUseDestroyersPluginsForBuildWorld()
	{
		$it = new SpecItemIt();
		$it->destroyers->add(function(){
			$this->foo = 'bar';
		});

		$it->setTestCallback(function() use(&$isApplyBeforeRun){
			$isApplyBeforeRun = (@$this->foo == 'bar');
		});

		$it->run();

		$this->assertFalse($isApplyBeforeRun);
	}

	public function testShouldBeApplyBuildersAndDestroyersToSharedWorld()
	{
		$it = new SpecItemIt();
		$it->builders->add(function() use(&$worlds, $it){
			$worlds['inBuilder'] = $this;
		});

		$it->setTestCallback(function() use(&$worlds, $it){
			$worlds['inTest'] = $this;
		});

		$it->destroyers->add(function() use(&$worlds, $it){
			$worlds['inDestroyer'] = $this;
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

		$specs[0]->builders->add(function(){ $this->callOrder[] = 0; });
		$specs[1]->builders->add(function(){ $this->callOrder[] = 1; });
		$specs[2]->builders->add(function(){ $this->callOrder[] = 2; });
		$specs[3]->builders->add(function(){ $this->callOrder[] = 3; });
		$specs[4]->builders->add(function(){ $this->callOrder[] = 4; });
		$specs[5]->builders->add(function(){ $this->callOrder[] = 5; });
		$specs[6]->builders->add(function(){ $this->callOrder[] = 6; });

		$specs[6]->setTestCallback(function() use(&$resultCallOrder){
			$resultCallOrder = $this->callOrder;
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