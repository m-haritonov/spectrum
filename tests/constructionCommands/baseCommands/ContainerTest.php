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

class ContainerTest extends \spectrum\constructionCommands\commands\Test
{
	public function testShouldBeReturnNewSpecContainerInstance()
	{
		$describe1 = manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){});
		$describe2 = manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){});

		$this->assertTrue($describe1 instanceof \spectrum\core\SpecContainerDescribeInterface);
		$this->assertTrue($describe2 instanceof \spectrum\core\SpecContainerDescribeInterface);
		$this->assertNotSame($describe1, $describe2);
	}

	public function testShouldBeReturnInstanceWithNoChild()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){});
		$this->assertSame(array(), $describe->getSpecs());
	}

	public function testShouldBeCallCallbackDuringCall()
	{
		manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) {
			$isCalled = true;
		});
		
		$this->assertTrue($isCalled);
	}

/**/

	public function testParamsVariants_ShouldNotBeAcceptStringFunctions()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', 'trim');
		$this->assertEquals('trim', $describe->getName());
	}

/**/

	public function testParamsVariants_ShouldBeAcceptName()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', 'foo');
		$this->assertEquals('foo', $describe->getName());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsString()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', 'foo', 'koi-8');
		$this->assertEquals('foo', $describe->getName());
		$this->assertEquals('koi-8', $describe->output->getInputEncoding());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsInteger()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', 'foo', 2);
		$this->assertEquals('foo', $describe->getName());
		$this->assertEquals(2, $describe->errorHandling->getCatchPhpErrors());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsBoolean()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', 'foo', true);
		$this->assertEquals('foo', $describe->getName());
		$this->assertEquals(-1, $describe->errorHandling->getCatchPhpErrors());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsArray()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', 'foo', array('inputEncoding' => 'koi-8'));
		$this->assertEquals('foo', $describe->getName());
		$this->assertEquals('koi-8', $describe->output->getInputEncoding());
	}

/**/

	public function testParamsVariants_ShouldBeAcceptCallback()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', function() use(&$isCalled) { $isCalled = true; });
		$this->assertNull($describe->getName());
		$this->assertTrue($isCalled);
	}
	
	public function testParamsVariants_ShouldBeAcceptCallbackAndSettingsString()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', function() use(&$isCalled) { $isCalled = true; }, 'koi-8');
		$this->assertNull($describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals('koi-8', $describe->output->getInputEncoding());
	}
	
	public function testParamsVariants_ShouldBeAcceptCallbackAndSettingsInteger()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', function() use(&$isCalled) { $isCalled = true; }, 2);
		$this->assertNull($describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals(2, $describe->errorHandling->getCatchPhpErrors());
	}
	
	public function testParamsVariants_ShouldBeAcceptCallbackAndSettingsBoolean()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', function() use(&$isCalled) { $isCalled = true; }, true);
		$this->assertNull($describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals(-1, $describe->errorHandling->getCatchPhpErrors());
	}
	
	public function testParamsVariants_ShouldBeAcceptCallbackAndSettingsArray()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', function() use(&$isCalled) { $isCalled = true; }, array('inputEncoding' => 'koi-8'));
		$this->assertNull($describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals('koi-8', $describe->output->getInputEncoding());
	}

/**/
		
	public function testParamsVariants_ShouldBeAcceptNameAndCallback()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) { $isCalled = true; });
		$this->assertEquals('foo', $describe->getName());
		$this->assertTrue($isCalled);
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndCallbackAndSettingsString()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) { $isCalled = true; }, 'koi-8');
		$this->assertEquals('foo', $describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals('koi-8', $describe->output->getInputEncoding());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndCallbackAndSettingsInteger()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) { $isCalled = true; }, 2);
		$this->assertEquals('foo', $describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals(2, $describe->errorHandling->getCatchPhpErrors());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndCallbackAndSettingsBoolean()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) { $isCalled = true; }, true);
		$this->assertEquals('foo', $describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals(-1, $describe->errorHandling->getCatchPhpErrors());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndCallbackAndSettingsArray()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) { $isCalled = true; }, array('inputEncoding' => 'koi-8'));
		$this->assertEquals('foo', $describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals('koi-8', $describe->output->getInputEncoding());
	}

/**/

	public function testFirstLevelContainer_ShouldBeAddInstanceToRootDescribe()
	{
		manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){});
		manager::container('\spectrum\core\SpecContainerDescribe', 'bar', function(){});
		manager::container('\spectrum\core\SpecContainerDescribe', 'baz', function(){});

		$rootSpecs = \spectrum\RootSpec::getOnceInstance()->getSpecs();

		$this->assertEquals(3, count($rootSpecs));
		$this->assertEquals('foo', $rootSpecs[0]->getName());
		$this->assertEquals('bar', $rootSpecs[1]->getName());
		$this->assertEquals('baz', $rootSpecs[2]->getName());
	}

	public function testFirstLevelContainer_ShouldNotBeAddInstanceToPreviousContainer()
	{
		$describe1 = manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
		manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});

		$this->assertSame(array(), $describe1->getSpecs());
	}

/**/

	public function testSecondLevelContainer_ShouldBeAddInstanceToParentContainer()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', '', function()
		{
			manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){});
			manager::container('\spectrum\core\SpecContainerDescribe', 'bar', function(){});
			manager::container('\spectrum\core\SpecContainerDescribe', 'baz', function(){});
		});

		$specs = $describe->getSpecs();

		$this->assertEquals(3, count($specs));
		$this->assertEquals('foo', $specs[0]->getName());
		$this->assertEquals('bar', $specs[1]->getName());
		$this->assertEquals('baz', $specs[2]->getName());
	}

	public function testSecondLevelContainer_ShouldNotBeAddInstanceToRootDescribe()
	{
		manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){
			manager::container('\spectrum\core\SpecContainerDescribe', 'bar', function(){});
		});

		$rootSpecs = \spectrum\RootSpec::getOnceInstance()->getSpecs();

		$this->assertEquals(1, count($rootSpecs));
		$this->assertEquals('foo', $rootSpecs[0]->getName());
	}

	public function testSecondLevelContainer_ShouldNotBeAddInstanceToPreviousContainer()
	{
		manager::container('\spectrum\core\SpecContainerDescribe', '', function() use(&$describe1)
		{
			$describe1 = manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
			manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
		});

		$this->assertSame(array(), $describe1->getSpecs());
	}

/**/

	public function testThirdLevelContainer_ShouldBeAddInstanceToParentContainer()
	{
		manager::container('\spectrum\core\SpecContainerDescribe', '', function() use(&$describe)
		{
			$describe = manager::container('\spectrum\core\SpecContainerDescribe', '', function()
			{
				manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){});
				manager::container('\spectrum\core\SpecContainerDescribe', 'bar', function(){});
				manager::container('\spectrum\core\SpecContainerDescribe', 'baz', function(){});
			});
		});

		$specs = $describe->getSpecs();

		$this->assertEquals(3, count($specs));
		$this->assertEquals('foo', $specs[0]->getName());
		$this->assertEquals('bar', $specs[1]->getName());
		$this->assertEquals('baz', $specs[2]->getName());
	}

	public function testThirdLevelContainer_ShouldNotBeAddInstanceToRootDescribe()
	{
		manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){
			manager::container('\spectrum\core\SpecContainerDescribe', '', function(){
				manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
			});
		});

		$rootSpecs = \spectrum\RootSpec::getOnceInstance()->getSpecs();

		$this->assertEquals(1, count($rootSpecs));
		$this->assertEquals('foo', $rootSpecs[0]->getName());
	}

	public function testThirdLevelContainer_ShouldNotBeAddInstanceToAncestorContainer()
	{
		$describe = manager::container('\spectrum\core\SpecContainerDescribe', '', function(){
			manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){
				manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
			});
		});

		$specs = $describe->getSpecs();

		$this->assertEquals(1, count($specs));
		$this->assertEquals('foo', $specs[0]->getName());
	}

	public function testThirdLevelContainer_ShouldNotBeAddInstanceToPreviousContainer()
	{
		manager::container('\spectrum\core\SpecContainerDescribe', '', function() use(&$describe1)
		{
			manager::container('\spectrum\core\SpecContainerDescribe', '', function() use(&$describe1)
			{
				$describe1 = manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
				manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
			});
		});

		$this->assertSame(array(), $describe1->getSpecs());
	}
}