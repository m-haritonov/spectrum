<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\spec;
use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class OtherTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::unregisterSpecPlugins();
	}
	
	public function testEnable_EnablesSpec()
	{
		$spec = new Spec();
		$spec->disable();
		$this->assertSame(false, $spec->isEnabled());
		$spec->enable();
		$this->assertSame(true, $spec->isEnabled());
	}
	
	public function testEnable_ThrowsExceptionWhenCallOnRun()
	{
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					$this->getOwnerSpec()->enable();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "enable" method is deny on running', function() use($spec){
			$spec->run();
		});
	}
	
/**/
	
	public function testDisable_DisablesSpec()
	{
		$spec = new Spec();
		$spec->enable();
		$this->assertSame(true, $spec->isEnabled());
		$spec->disable();
		$this->assertSame(false, $spec->isEnabled());
	}
	
	public function testDisable_ThrowsExceptionWhenCallOnRun()
	{
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					$this->getOwnerSpec()->disable();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "disable" method is deny on running', function() use($spec){
			$spec->run();
		});
	}
	
/**/
	
	public function testIsEnabled_ReturnsTrueForEnabledSpec()
	{
		$spec = new Spec();
		$spec->enable();
		$this->assertSame(true, $spec->isEnabled());
	}
	
	public function testIsEnabled_ReturnsFalseForDisabledSpec()
	{
		$spec = new Spec();
		$spec->disable();
		$this->assertSame(false, $spec->isEnabled());
	}
	
/**/
	
	public function testSetName_SetsSpecName()
	{
		$spec = new Spec();
		
		$spec->setName('aaa');
		$this->assertSame('aaa', $spec->getName());
		
		$spec->setName('bbb');
		$this->assertSame('bbb', $spec->getName());
	}
	
	public function testSetName_ThrowsExceptionWhenCallOnRun()
	{
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					$this->getOwnerSpec()->setName("aaa");
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "setName" method is deny on running', function() use($spec){
			$spec->run();
		});
	}
	
/**/
	
	public function testGetName_ReturnsSpecName()
	{
		$spec = new Spec();
		$spec->setName('aaa');
		$this->assertSame('aaa', $spec->getName());
	}
	
	public function testGetName_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->getName());
	}
	
/**/
	
	public function testIsAnonymous_ReturnsTrueForSpecWithEmptyNameAndWithChildren()
	{
		$spec = new Spec();
		$spec->bindChildSpec(new Spec());
		$this->assertSame(true, $spec->isAnonymous());
		
		$spec->setName(null);
		$this->assertSame(true, $spec->isAnonymous());
		
		$spec->setName('');
		$this->assertSame(true, $spec->isAnonymous());
	}
	
	public function testIsAnonymous_ReturnsFalseForSpecWithNoEmptyNameOrWithoutChildren()
	{
		$spec = new Spec();
		$spec->setName('aaa');
		$this->assertSame(false, $spec->isAnonymous());
		
		$spec = new Spec();
		$this->assertSame(false, $spec->isAnonymous());
		
		$spec = new Spec();
		$spec->setName('aaa');
		$spec->bindChildSpec(new Spec());
		$this->assertSame(false, $spec->isAnonymous());
	}
	
/**/
	
	public function testGetParentSpecs_ReturnsEmptyArrayByDefault()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->getParentSpecs());
	}
	
/**/
	
	public function testHasParentSpec_ReturnsTrueForBoundSpec()
	{
		$spec = new Spec();
		$parentSpec = new Spec();
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(true, $spec->hasParentSpec($parentSpec));
	}
	
	public function testHasParentSpec_ReturnsFalseForNotBoundSpec()
	{
		$spec = new Spec();
		$spec->bindParentSpec(new Spec());
		$this->assertSame(false, $spec->hasParentSpec(new Spec()));
	}
	
/**/
	
	public function testBindParentSpec_CreatesConnectionBetweenSpecs()
	{
		$spec = new Spec();
		$parentSpec = new Spec();
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(array($parentSpec), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec->getChildSpecs());
	}
	
	public function testBindParentSpec_DoesNotCreateConnectionBetweenAlreadyConnectedSpecs()
	{
		$spec = new Spec();
		$parentSpec = new Spec();
		
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(array($parentSpec), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec->getChildSpecs());
		
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(array($parentSpec), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec->getChildSpecs());
	}
	
	public function testBindParentSpec_ThrowsExceptionWhenCallOnRun()
	{
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					$this->getOwnerSpec()->bindParentSpec(new \spectrum\core\Spec());
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "bindParentSpec" method is deny on running', function() use($spec){
			$spec->run();
		});
	}
	
/**/
	
	public function testUnbindParentSpec_BreaksConnectionBetweenSpecs()
	{
		$spec = new Spec();
		$parentSpec = new Spec();
		
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(array($parentSpec), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec->getChildSpecs());
		
		$spec->unbindParentSpec($parentSpec);
		$this->assertSame(array(), $spec->getParentSpecs());
		$this->assertSame(array(), $parentSpec->getChildSpecs());
	}
	
	public function testUnbindParentSpec_DoesNotBreaksOtherConnections()
	{
		$spec = new Spec();
		$parentSpec1 = new Spec();
		$parentSpec2 = new Spec();
		
		$spec->bindParentSpec($parentSpec1);
		$spec->bindParentSpec($parentSpec2);
		$this->assertSame(array($parentSpec1, $parentSpec2), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec1->getChildSpecs());
		$this->assertSame(array($spec), $parentSpec2->getChildSpecs());
		
		$spec->unbindParentSpec($parentSpec1);
		$this->assertSame(array($parentSpec2), $spec->getParentSpecs());
		$this->assertSame(array(), $parentSpec1->getChildSpecs());
		$this->assertSame(array($spec), $parentSpec2->getChildSpecs());
	}
	
	public function testUnbindParentSpec_DoesNotTriggersErrorWhenNoConnectionBetweenSpecs()
	{
		$spec = new Spec();
		$parentSpec = new Spec();
		
		$spec->unbindParentSpec($parentSpec);
		$this->assertSame(array(), $spec->getParentSpecs());
		$this->assertSame(array(), $parentSpec->getChildSpecs());
	}
	
	public function testUnbindParentSpec_ResetsArrayIndexes()
	{
		$spec = new Spec();
		$parentSpec1 = new Spec();
		$parentSpec2 = new Spec();
		$parentSpec3 = new Spec();
		
		$spec->bindParentSpec($parentSpec1);
		$spec->bindParentSpec($parentSpec2);
		$spec->bindParentSpec($parentSpec3);
		
		$spec->unbindParentSpec($parentSpec2);
		$this->assertSame(array($parentSpec1, $parentSpec3), $spec->getParentSpecs());
	}
	
	public function testUnbindParentSpec_ResetsArrayIndexesInUnboundSpec()
	{
		$spec = new Spec();
		$childSpec1 = new Spec();
		$childSpec2 = new Spec();
		$childSpec3 = new Spec();
		
		$spec->bindChildSpec($childSpec1);
		$spec->bindChildSpec($childSpec2);
		$spec->bindChildSpec($childSpec3);
		
		$childSpec2->unbindParentSpec($spec);
		$this->assertSame(array($childSpec1, $childSpec3), $spec->getChildSpecs());
	}
	
	public function testUnbindParentSpec_ThrowsExceptionWhenCallOnRun()
	{
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					$this->getOwnerSpec()->unbindParentSpec(new \spectrum\core\Spec());
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "unbindParentSpec" method is deny on running', function() use($spec){
			$spec->run();
		});
	}
	
/**/
	
	public function testUnbindAllParentSpecs_BreaksConnectionsWithAllParentSpecs()
	{
		$spec = new Spec();
		$parentSpec1 = new Spec();
		$parentSpec2 = new Spec();
		
		$spec->bindParentSpec($parentSpec1);
		$spec->bindParentSpec($parentSpec2);
		$this->assertSame(array($parentSpec1, $parentSpec2), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec1->getChildSpecs());
		$this->assertSame(array($spec), $parentSpec2->getChildSpecs());
		
		$spec->unbindAllParentSpecs();
		$this->assertSame(array(), $spec->getParentSpecs());
		$this->assertSame(array(), $parentSpec1->getChildSpecs());
		$this->assertSame(array(), $parentSpec2->getChildSpecs());
	}
	
	public function testUnbindAllParentSpecs_ResetsArrayIndexes()
	{
		$spec = new Spec();
		$parentSpec1 = new Spec();
		$parentSpec2 = new Spec();
		
		$spec->bindParentSpec($parentSpec1);
		$spec->bindParentSpec($parentSpec2);
		$this->assertSame(array($parentSpec1, $parentSpec2), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec1->getChildSpecs());
		$this->assertSame(array($spec), $parentSpec2->getChildSpecs());
		
		$spec->unbindAllParentSpecs();
		$parentSpec3 = new Spec();
		$spec->bindParentSpec($parentSpec3);
		$this->assertSame(array($parentSpec3), $spec->getParentSpecs());
	}
	
	public function testUnbindAllParentSpecs_ResetsArrayIndexesInUnboundSpec()
	{
		$spec = new Spec();
		$childSpec1 = new Spec();
		$childSpec2 = new Spec();
		$childSpec3 = new Spec();
		
		$spec->bindChildSpec($childSpec1);
		$spec->bindChildSpec($childSpec2);
		$spec->bindChildSpec($childSpec3);
		
		$childSpec2->unbindAllParentSpecs();
		$this->assertSame(array($childSpec1, $childSpec3), $spec->getChildSpecs());
	}
	
	public function testUnbindAllParentSpecs_ThrowsExceptionWhenCallOnRun()
	{
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					$this->getOwnerSpec()->unbindAllParentSpecs();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "unbindAllParentSpecs" method is deny on running', function() use($spec){
			$spec->run();
		});
	}
	
/**/
	
	public function testGetChildSpecs_ReturnsEmptyArrayByDefault()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->getChildSpecs());
	}
	
/**/
	
	public function testGetChildSpecsByName_ReturnsAllSpecsWithProperName()
	{
		$spec = new Spec();
		
		$childSpec1 = new Spec();
		$childSpec1->setName('aaa');
		$spec->bindChildSpec($childSpec1);
		
		$childSpec2 = new Spec();
		$childSpec2->setName('bbb');
		$spec->bindChildSpec($childSpec2);
		
		$childSpec3 = new Spec();
		$childSpec3->setName('aaa');
		$spec->bindChildSpec($childSpec3);
		
		$this->assertSame(array($childSpec1, $childSpec3), $spec->getChildSpecsByName('aaa'));
		$this->assertSame(array($childSpec2), $spec->getChildSpecsByName('bbb'));
	}
	
	public function testGetChildSpecsByName_ReturnsEmptyArrayWhenNoProperChildren()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->getChildSpecsByName('aaa'));
	}
	
/**/
	
	public function testGetChildSpecByNumber_ReturnsSpecWithProperNumber()
	{
		$spec = new Spec();
		
		$childSpec1 = new Spec();
		$spec->bindChildSpec($childSpec1);
		
		$childSpec2 = new Spec();
		$spec->bindChildSpec($childSpec2);
		
		$childSpec3 = new Spec();
		$spec->bindChildSpec($childSpec3);
		
		$this->assertSame($childSpec1, $spec->getChildSpecByNumber(1));
		$this->assertSame($childSpec2, $spec->getChildSpecByNumber(2));
		$this->assertSame($childSpec3, $spec->getChildSpecByNumber(3));
	}
	
	public function testGetChildSpecByNumber_ReturnsNullWhenNoChildWithProperNumber()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->getChildSpecByNumber(1));
	}
	
/**/
	
	public function testHasChildSpec_ReturnsTrueForBoundSpec()
	{
		$spec = new Spec();
		$childSpec = new Spec();
		$spec->bindChildSpec($childSpec);
		$this->assertSame(true, $spec->hasChildSpec($childSpec));
	}
	
	public function testHasChildSpec_ReturnsFalseForNotBoundSpec()
	{
		$spec = new Spec();
		$spec->bindChildSpec(new Spec());
		$this->assertSame(false, $spec->hasChildSpec(new Spec()));
	}
	
/**/
	
	public function testBindChildSpec_CreatesConnectionBetweenSpecs()
	{
		$spec = new Spec();
		$childSpec = new Spec();
		$spec->bindChildSpec($childSpec);
		$this->assertSame(array($childSpec), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec->getParentSpecs());
	}
	
	public function testBindChildSpec_DoesNotCreateConnectionBetweenAlreadyConnectedSpecs()
	{
		$spec = new Spec();
		$childSpec = new Spec();
		
		$spec->bindChildSpec($childSpec);
		$this->assertSame(array($childSpec), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec->getParentSpecs());
		
		$spec->bindChildSpec($childSpec);
		$this->assertSame(array($childSpec), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec->getParentSpecs());
	}
	
	public function testBindChildSpec_ThrowsExceptionWhenCallOnRun()
	{
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					$this->getOwnerSpec()->bindChildSpec(new \spectrum\core\Spec());
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "bindChildSpec" method is deny on running', function() use($spec){
			$spec->run();
		});
	}
	
/**/
	
	public function testUnbindChildSpec_BreaksConnectionBetweenSpecs()
	{
		$spec = new Spec();
		$childSpec = new Spec();
		
		$spec->bindChildSpec($childSpec);
		$this->assertSame(array($childSpec), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec->getParentSpecs());
		
		$spec->unbindChildSpec($childSpec);
		$this->assertSame(array(), $spec->getChildSpecs());
		$this->assertSame(array(), $childSpec->getParentSpecs());
	}
	
	public function testUnbindChildSpec_DoesNotBreaksOtherConnections()
	{
		$spec = new Spec();
		$childSpec1 = new Spec();
		$childSpec2 = new Spec();
		
		$spec->bindChildSpec($childSpec1);
		$spec->bindChildSpec($childSpec2);
		$this->assertSame(array($childSpec1, $childSpec2), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec1->getParentSpecs());
		$this->assertSame(array($spec), $childSpec2->getParentSpecs());
		
		$spec->unbindChildSpec($childSpec1);
		$this->assertSame(array($childSpec2), $spec->getChildSpecs());
		$this->assertSame(array(), $childSpec1->getParentSpecs());
		$this->assertSame(array($spec), $childSpec2->getParentSpecs());
	}
	
	public function testUnbindChildSpec_DoesNotTriggersErrorWhenNoConnectionBetweenSpecs()
	{
		$spec = new Spec();
		$childSpec = new Spec();
		
		$spec->unbindChildSpec($childSpec);
		$this->assertSame(array(), $spec->getChildSpecs());
		$this->assertSame(array(), $childSpec->getParentSpecs());
	}
	
	public function testUnbindChildSpec_ResetsArrayIndexes()
	{
		$spec = new Spec();
		$childSpec1 = new Spec();
		$childSpec2 = new Spec();
		$childSpec3 = new Spec();
		
		$spec->bindChildSpec($childSpec1);
		$spec->bindChildSpec($childSpec2);
		$spec->bindChildSpec($childSpec3);
		
		$spec->unbindChildSpec($childSpec2);
		$this->assertSame(array($childSpec1, $childSpec3), $spec->getChildSpecs());
	}
	
	public function testUnbindChildSpec_ResetsArrayIndexesInUnboundSpec()
	{
		$spec = new Spec();
		$parentSpec1 = new Spec();
		$parentSpec2 = new Spec();
		$parentSpec3 = new Spec();
		
		$spec->bindParentSpec($parentSpec1);
		$spec->bindParentSpec($parentSpec2);
		$spec->bindParentSpec($parentSpec3);
		
		$parentSpec2->unbindChildSpec($spec);
		$this->assertSame(array($parentSpec1, $parentSpec3), $spec->getParentSpecs());
	}

	public function testUnbindChildSpec_ThrowsExceptionWhenCallOnRun()
	{
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					$this->getOwnerSpec()->unbindChildSpec(new \spectrum\core\Spec());
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "unbindChildSpec" method is deny on running', function() use($spec){
			$spec->run();
		});
	}
	
/**/
	
	public function testUnbindAllChildSpecs_BreaksConnectionsWithAllChildSpecs()
	{
		$spec = new Spec();
		$childSpec1 = new Spec();
		$childSpec2 = new Spec();
		
		$spec->bindChildSpec($childSpec1);
		$spec->bindChildSpec($childSpec2);
		$this->assertSame(array($childSpec1, $childSpec2), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec1->getParentSpecs());
		$this->assertSame(array($spec), $childSpec2->getParentSpecs());
		
		$spec->unbindAllChildSpecs();
		$this->assertSame(array(), $spec->getChildSpecs());
		$this->assertSame(array(), $childSpec1->getParentSpecs());
		$this->assertSame(array(), $childSpec2->getParentSpecs());
	}
	
	public function testUnbindAllChildSpecs_ResetsArrayIndexes()
	{
		$spec = new Spec();
		$childSpec1 = new Spec();
		$childSpec2 = new Spec();
		
		$spec->bindChildSpec($childSpec1);
		$spec->bindChildSpec($childSpec2);
		$this->assertSame(array($childSpec1, $childSpec2), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec1->getParentSpecs());
		$this->assertSame(array($spec), $childSpec2->getParentSpecs());
		
		$spec->unbindAllChildSpecs();
		$childSpec3 = new Spec();
		$spec->bindChildSpec($childSpec3);
		$this->assertSame(array($childSpec3), $spec->getChildSpecs());
	}
	
	public function testUnbindAllChildSpecs_ResetsArrayIndexesInUnboundSpec()
	{
		$spec = new Spec();
		$parentSpec1 = new Spec();
		$parentSpec2 = new Spec();
		$parentSpec3 = new Spec();
		
		$spec->bindParentSpec($parentSpec1);
		$spec->bindParentSpec($parentSpec2);
		$spec->bindParentSpec($parentSpec3);
		
		$parentSpec2->unbindAllChildSpecs();
		$this->assertSame(array($parentSpec1, $parentSpec3), $spec->getParentSpecs());
	}

	public function testUnbindAllChildSpecs_ThrowsExceptionWhenCallOnRun()
	{
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					$this->getOwnerSpec()->unbindAllChildSpecs();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "unbindAllChildSpecs" method is deny on running', function() use($spec){
			$spec->run();
		});
	}

/**/

	public function testGetRootSpec_ReturnsRootSpec()
	{
		$specs = $this->createSpecsTree('
			->Spec
			Spec(aaa)
		');
		$this->assertSame($specs[0], $specs['aaa']->getRootSpec());
		
		$specs = $this->createSpecsTree('
			->->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame($specs[0], $specs['aaa']->getRootSpec());
		
		$specs = $this->createSpecsTree('
			->->->Spec
			->->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame($specs[0], $specs['aaa']->getRootSpec());
	}
	
	public function testGetRootSpec_ReturnsSelfSpecForSpecWithoutParent()
	{
		$spec = new Spec();
		$this->assertSame($spec, $spec->getRootSpec());
		
		$spec = new Spec();
		$spec->bindChildSpec(new Spec());
		$this->assertSame($spec, $spec->getRootSpec());
	}
	
/**/
	
	public function testGetRootSpecs_ReturnsAllRootSpecs()
	{
		$specs = $this->createSpecsTree('
			->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame(array($specs[0], $specs[1]), $specs['aaa']->getRootSpecs());
		
		$specs = $this->createSpecsTree('
			->->Spec
			->Spec
			->->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame(array($specs[0], $specs[2]), $specs['aaa']->getRootSpecs());
	}
	
	public function testGetRootSpecs_DoesNotReturnSelfSpecForSpecWithoutParent()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->getRootSpecs());
		
		$spec = new Spec();
		$spec->bindChildSpec(new Spec());
		$this->assertSame(array(), $spec->getRootSpecs());
	}
	
/**/
	
	public function testGetRunningParentSpec_ReturnsRunningParentSpec()
	{
		\spectrum\tests\Test::$temp["specs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					if (\spectrum\tests\Test::$temp["checkpoint"] === $this->getOwnerSpec())
						\spectrum\tests\Test::$temp["specs"][] = $this->getOwnerSpec()->getRunningParentSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			->Spec
			->Spec
			Spec
		');
		
		$rootSpec = new Spec();
		$rootSpec->bindChildSpec($specs[0]);
		$rootSpec->bindChildSpec($specs[1]);
		
		\spectrum\tests\Test::$temp["checkpoint"] = $specs[2];
		$rootSpec->run();
		
		$this->assertSame(array($specs[0], $specs[1]), \spectrum\tests\Test::$temp["specs"]);
	}
	
	public function testGetRunningParentSpec_ReturnsNullWhenNoRunningParentSpec()
	{
		$specs = $this->createSpecsTree('
			->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame(null, $specs['aaa']->getRunningParentSpec());
	}
	
/**/

	public function testGetRunningAncestorSpecs_ReturnsRunningAncestorSpecs()
	{
		\spectrum\tests\Test::$temp["specs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					if (\spectrum\tests\Test::$temp["checkpoint"] === $this->getOwnerSpec())
						\spectrum\tests\Test::$temp["specs"][] = $this->getOwnerSpec()->getRunningAncestorSpecs();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			->Spec
			->Spec
			Spec
		');
		
		$rootSpec = new Spec();
		$rootSpec->bindChildSpec($specs[0]);
		$rootSpec->bindChildSpec($specs[1]);
		
		\spectrum\tests\Test::$temp["checkpoint"] = $specs[2];
		$rootSpec->run();
		
		$this->assertSame(array(
			array($specs[0], $rootSpec),
			array($specs[1], $rootSpec),
		), \spectrum\tests\Test::$temp["specs"]);
	}
	
	public function testGetRunningAncestorSpecs_ReturnsEmptyArrayWhenNoRunningParentSpec()
	{
		$specs = $this->createSpecsTree('
			->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame(array(), $specs['aaa']->getRunningAncestorSpecs());
	}

/**/
	
	public function testGetDeepestRunningSpec_ReturnsDeepestRunningChildSpec()
	{
		\spectrum\tests\Test::$temp["specs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["specs"][] = \spectrum\tests\Test::$temp["rootSpec"]->getDeepestRunningSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
		');
		
		\spectrum\tests\Test::$temp["rootSpec"] = $specs[0];
		$specs[0]->run();
		
		$this->assertSame(array($specs[0], $specs[1], $specs[2]), \spectrum\tests\Test::$temp["specs"]);
	}	
	
	public function testGetDeepestRunningSpec_ReturnsSelfWhenNoRunningChildrenAndSelfIsRunning()
	{
		\spectrum\tests\Test::$temp["specs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					if (\spectrum\tests\Test::$temp["checkpoint"] === $this->getOwnerSpec())
						\spectrum\tests\Test::$temp["specs"][] = $this->getOwnerSpec()->getDeepestRunningSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		\spectrum\tests\Test::$temp["checkpoint"] = $specs[0];
		$specs[0]->run();
		
		$this->assertSame(array($specs[0]), \spectrum\tests\Test::$temp["specs"]);
	}	
	
	public function testGetDeepestRunningSpec_ReturnsNullWhenNoRunningChildren()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->getDeepestRunningSpec());
	}
	
/**/
	
	public function testGetRunningChildSpec_ReturnsRunningChildSpec()
	{
		\spectrum\tests\Test::$temp["returnSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onEndingSpecExecute", "method" => "onEndingSpecExecute", "order" => 100),
					);
				}
				
				public function onEndingSpecExecute()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"]["checkpoint"])
					{
						\spectrum\tests\Test::$temp["returnSpecs"][] = \spectrum\tests\Test::$temp["specs"][0]->getRunningChildSpec();
						\spectrum\tests\Test::$temp["returnSpecs"][] = \spectrum\tests\Test::$temp["specs"][1]->getRunningChildSpec();
						\spectrum\tests\Test::$temp["returnSpecs"][] = \spectrum\tests\Test::$temp["specs"]["checkpoint"]->getRunningChildSpec();
					}
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec(checkpoint)
		');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		
		$this->assertSame(array(
			\spectrum\tests\Test::$temp["specs"][1],
			\spectrum\tests\Test::$temp["specs"]["checkpoint"],
			null,
		), \spectrum\tests\Test::$temp["returnSpecs"]);
	}	
	
	public function testGetRunningChildSpec_ReturnsNullWhenNoRunningChildren()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->getRunningChildSpec());
	}
	
/**/
	
	public function testGetResultBuffer_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->getResultBuffer());
	}
	
/**/
	
	public function testIsRunning_ReturnsFalseByDefault()
	{
		$spec = new Spec();
		$this->assertSame(false, $spec->isRunning());
	}
}