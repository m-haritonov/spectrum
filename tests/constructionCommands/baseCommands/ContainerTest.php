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

class ContainerTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeReturnNewSpecContainerInstance()
	{
		$describe1 = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){});
		$describe2 = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){});

		$this->assertTrue($describe1 instanceof \spectrum\core\SpecContainerDescribeInterface);
		$this->assertTrue($describe2 instanceof \spectrum\core\SpecContainerDescribeInterface);
		$this->assertNotSame($describe1, $describe2);
	}

	public function testShouldBeReturnInstanceWithNoChild()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){});
		$this->assertSame(array(), $describe->getSpecs());
	}

	public function testShouldBeCallCallbackDuringCall()
	{
		Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) {
			$isCalled = true;
		});
		
		$this->assertTrue($isCalled);
	}

/**/

	public function testParamsVariants_ShouldNotBeAcceptStringFunctions()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', 'trim');
		$this->assertEquals('trim', $describe->getName());
	}

/**/

	public function testParamsVariants_ShouldBeAcceptName()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo');
		$this->assertEquals('foo', $describe->getName());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsString()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', 'koi-8');
		$this->assertEquals('foo', $describe->getName());
		$this->assertEquals('koi-8', $describe->output->getInputEncoding());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsInteger()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', 2);
		$this->assertEquals('foo', $describe->getName());
		$this->assertEquals(2, $describe->errorHandling->getCatchPhpErrors());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsBoolean()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', true);
		$this->assertEquals('foo', $describe->getName());
		$this->assertEquals(-1, $describe->errorHandling->getCatchPhpErrors());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsArray()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', array('inputEncoding' => 'koi-8'));
		$this->assertEquals('foo', $describe->getName());
		$this->assertEquals('koi-8', $describe->output->getInputEncoding());
	}

/**/

	public function testParamsVariants_ShouldBeAcceptCallback()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', function() use(&$isCalled) { $isCalled = true; });
		$this->assertNull($describe->getName());
		$this->assertTrue($isCalled);
	}
	
	public function testParamsVariants_ShouldBeAcceptCallbackAndSettingsString()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', function() use(&$isCalled) { $isCalled = true; }, 'koi-8');
		$this->assertNull($describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals('koi-8', $describe->output->getInputEncoding());
	}
	
	public function testParamsVariants_ShouldBeAcceptCallbackAndSettingsInteger()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', function() use(&$isCalled) { $isCalled = true; }, 2);
		$this->assertNull($describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals(2, $describe->errorHandling->getCatchPhpErrors());
	}
	
	public function testParamsVariants_ShouldBeAcceptCallbackAndSettingsBoolean()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', function() use(&$isCalled) { $isCalled = true; }, true);
		$this->assertNull($describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals(-1, $describe->errorHandling->getCatchPhpErrors());
	}
	
	public function testParamsVariants_ShouldBeAcceptCallbackAndSettingsArray()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', function() use(&$isCalled) { $isCalled = true; }, array('inputEncoding' => 'koi-8'));
		$this->assertNull($describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals('koi-8', $describe->output->getInputEncoding());
	}

/**/
		
	public function testParamsVariants_ShouldBeAcceptNameAndCallback()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) { $isCalled = true; });
		$this->assertEquals('foo', $describe->getName());
		$this->assertTrue($isCalled);
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndCallbackAndSettingsString()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) { $isCalled = true; }, 'koi-8');
		$this->assertEquals('foo', $describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals('koi-8', $describe->output->getInputEncoding());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndCallbackAndSettingsInteger()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) { $isCalled = true; }, 2);
		$this->assertEquals('foo', $describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals(2, $describe->errorHandling->getCatchPhpErrors());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndCallbackAndSettingsBoolean()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) { $isCalled = true; }, true);
		$this->assertEquals('foo', $describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals(-1, $describe->errorHandling->getCatchPhpErrors());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndCallbackAndSettingsArray()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) { $isCalled = true; }, array('inputEncoding' => 'koi-8'));
		$this->assertEquals('foo', $describe->getName());
		$this->assertTrue($isCalled);
		$this->assertEquals('koi-8', $describe->output->getInputEncoding());
	}

/**/

	public function testFirstLevelContainer_ShouldBeAddInstanceToRootDescribe()
	{
		Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){});
		Manager::container('\spectrum\core\SpecContainerDescribe', 'bar', function(){});
		Manager::container('\spectrum\core\SpecContainerDescribe', 'baz', function(){});

		$rootSpecs = \spectrum\RootDescribe::getOnceInstance()->getSpecs();

		$this->assertEquals(3, count($rootSpecs));
		$this->assertEquals('foo', $rootSpecs[0]->getName());
		$this->assertEquals('bar', $rootSpecs[1]->getName());
		$this->assertEquals('baz', $rootSpecs[2]->getName());
	}

	public function testFirstLevelContainer_ShouldNotBeAddInstanceToPreviousContainer()
	{
		$describe1 = Manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
		Manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});

		$this->assertSame(array(), $describe1->getSpecs());
	}

/**/

	public function testSecondLevelContainer_ShouldBeAddInstanceToParentContainer()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', '', function()
		{
			Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){});
			Manager::container('\spectrum\core\SpecContainerDescribe', 'bar', function(){});
			Manager::container('\spectrum\core\SpecContainerDescribe', 'baz', function(){});
		});

		$specs = $describe->getSpecs();

		$this->assertEquals(3, count($specs));
		$this->assertEquals('foo', $specs[0]->getName());
		$this->assertEquals('bar', $specs[1]->getName());
		$this->assertEquals('baz', $specs[2]->getName());
	}

	public function testSecondLevelContainer_ShouldNotBeAddInstanceToRootDescribe()
	{
		Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){
			Manager::container('\spectrum\core\SpecContainerDescribe', 'bar', function(){});
		});

		$rootSpecs = \spectrum\RootDescribe::getOnceInstance()->getSpecs();

		$this->assertEquals(1, count($rootSpecs));
		$this->assertEquals('foo', $rootSpecs[0]->getName());
	}

	public function testSecondLevelContainer_ShouldNotBeAddInstanceToPreviousContainer()
	{
		Manager::container('\spectrum\core\SpecContainerDescribe', '', function() use(&$describe1)
		{
			$describe1 = Manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
			Manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
		});

		$this->assertSame(array(), $describe1->getSpecs());
	}

/**/

	public function testThirdLevelContainer_ShouldBeAddInstanceToParentContainer()
	{
		Manager::container('\spectrum\core\SpecContainerDescribe', '', function() use(&$describe)
		{
			$describe = Manager::container('\spectrum\core\SpecContainerDescribe', '', function()
			{
				Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){});
				Manager::container('\spectrum\core\SpecContainerDescribe', 'bar', function(){});
				Manager::container('\spectrum\core\SpecContainerDescribe', 'baz', function(){});
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
		Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){
			Manager::container('\spectrum\core\SpecContainerDescribe', '', function(){
				Manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
			});
		});

		$rootSpecs = \spectrum\RootDescribe::getOnceInstance()->getSpecs();

		$this->assertEquals(1, count($rootSpecs));
		$this->assertEquals('foo', $rootSpecs[0]->getName());
	}

	public function testThirdLevelContainer_ShouldNotBeAddInstanceToAncestorContainer()
	{
		$describe = Manager::container('\spectrum\core\SpecContainerDescribe', '', function(){
			Manager::container('\spectrum\core\SpecContainerDescribe', 'foo', function(){
				Manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
			});
		});

		$specs = $describe->getSpecs();

		$this->assertEquals(1, count($specs));
		$this->assertEquals('foo', $specs[0]->getName());
	}

	public function testThirdLevelContainer_ShouldNotBeAddInstanceToPreviousContainer()
	{
		Manager::container('\spectrum\core\SpecContainerDescribe', '', function() use(&$describe1)
		{
			Manager::container('\spectrum\core\SpecContainerDescribe', '', function() use(&$describe1)
			{
				$describe1 = Manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
				Manager::container('\spectrum\core\SpecContainerDescribe', '', function(){});
			});
		});

		$this->assertSame(array(), $describe1->getSpecs());
	}
}