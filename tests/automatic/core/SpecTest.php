<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core;

use spectrum\config;
use spectrum\core\Assertion;
use spectrum\core\ContextModifiers;
use spectrum\core\Data;
use spectrum\core\ErrorHandling;
use spectrum\core\Matchers;
use spectrum\core\Messages;
use spectrum\core\ResultBuffer;
use spectrum\core\Spec;
use spectrum\core\SpecInterface;
use spectrum\core\Executor;

require_once __DIR__ . '/../../init.php';

class SpecTest extends \spectrum\tests\automatic\Test {
	public function testConstruct_EventDispatch_OnSpecConstruct_CallsEventListenersInSpecifiedSequence() {
		$this->patternCallsEventListenersInSpecifiedSequence('onSpecConstruct');
	}
	
	public function testConstruct_EventDispatch_OnSpecConstruct_CallsEventListenersWithSameOrderInRegistrationSequence() {
		$this->patternCallsEventListenersWithSameOrderInRegistrationSequence('onSpecConstruct');
	}
	
	public function testConstruct_EventDispatch_OnSpecConstruct_IsDispatchedOnSpecInstanceCreation() {
		$createdSpecs = array();
		config::registerEventListener('onSpecConstruct', function(SpecInterface $spec) use(&$createdSpecs) { $createdSpecs[] = $spec; });
		$specs = array(new Spec(), new Spec(), new Spec());
		$this->assertSame($specs, $createdSpecs);
	}
	
	public function testConstruct_EventDispatch_OnSpecConstruct_PassesSpecToEventListeners() {
		$passedArguments = array();
		config::registerEventListener('onSpecConstruct', function() use(&$passedArguments) {
			$passedArguments[] = func_get_args();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$this->assertSame(array(array($specs[0]), array($specs[1])), $passedArguments);
	}
	
/**/
	
	public function testEnable_EnablesSpec() {
		$spec = new Spec();
		$spec->disable();
		$this->assertSame(false, $spec->isEnabled());
		$spec->enable();
		$this->assertSame(true, $spec->isEnabled());
	}
	
	public function testEnable_CallOnRun_ThrowsExceptionAndDoesNotEnableSpec() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		config::registerEventListener('onSpecRunStart', function() use($specs) {
			$specs[1]->enable();
		});
		
		$specs[1]->disable();
		
		$this->assertThrowsException('\spectrum\Exception', 'Call of "\spectrum\core\Spec::enable" method is forbidden on run', function() use($specs) {
			$specs[0]->run();
		});
		
		$this->assertSame(false, $specs[1]->isEnabled());
	}
	
/**/
	
	public function testDisable_DisablesSpec() {
		$spec = new Spec();
		$spec->enable();
		$this->assertSame(true, $spec->isEnabled());
		$spec->disable();
		$this->assertSame(false, $spec->isEnabled());
	}
	
	public function testDisable_CallOnRun_ThrowsExceptionAndDoesNotDisableSpec() {
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) {
			$spec->disable();
		});
		
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\Exception', 'Call of "\spectrum\core\Spec::disable" method is forbidden on run', function() use($spec) {
			$spec->run();
		});
		
		$this->assertSame(true, $spec->isEnabled());
	}
	
/**/
	
	public function testIsEnabled_ReturnsTrueForEnabledSpec() {
		$spec = new Spec();
		$spec->enable();
		$this->assertSame(true, $spec->isEnabled());
	}
	
	public function testIsEnabled_ReturnsFalseForDisabledSpec() {
		$spec = new Spec();
		$spec->disable();
		$this->assertSame(false, $spec->isEnabled());
	}

/**/

	public function testSetName_SetsSpecName() {
		$spec = new Spec();
		
		$spec->setName('aaa');
		$this->assertSame('aaa', $spec->getName());
		
		$spec->setName('bbb');
		$this->assertSame('bbb', $spec->getName());
	}
	
	public function testSetName_CallOnRun_ThrowsExceptionAndDoesNotChangeName() {
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) {
			$spec->setName("bbb");
		});
		$spec = new Spec();
		$spec->setName('aaa');
		
		$this->assertThrowsException('\spectrum\Exception', 'Call of "\spectrum\core\Spec::setName" method is forbidden on run', function() use($spec){
			$spec->run();
		});
		
		$this->assertSame('aaa', $spec->getName());
	}
	
/**/
	
	public function testGetName_ReturnsSpecName() {
		$spec = new Spec();
		$spec->setName('aaa');
		$this->assertSame('aaa', $spec->getName());
	}
	
	public function testGetName_ReturnsNullByDefault() {
		$spec = new Spec();
		$this->assertSame(null, $spec->getName());
	}
	
/**/
	
	public function testIsAnonymous_NameIsNotSetAndSpecHasChildren_ReturnsTrue() {
		$spec = new Spec();
		$spec->bindChildSpec(new Spec());
		$this->assertSame(true, $spec->isAnonymous());
	}
	
	public function testIsAnonymous_NameIsNotSetAndSpecHasNoChildren_ReturnsFalse() {
		$spec = new Spec();
		$this->assertSame(false, $spec->isAnonymous());
	}
	
	public function testIsAnonymous_NameIsNullAndSpecHasChildren_ReturnsTrue() {
		$spec = new Spec();
		$spec->setName(null);
		$spec->bindChildSpec(new Spec());
		$this->assertSame(true, $spec->isAnonymous());
	}
	
	public function testIsAnonymous_NameIsNullAndSpecHasNoChildren_ReturnsFalse() {
		$spec = new Spec();
		$spec->setName(null);
		$this->assertSame(false, $spec->isAnonymous());
	}

	public function testIsAnonymous_NameIsNotEmptyStringAndSpecHasChildren_ReturnsFalse() {
		$spec = new Spec();
		$spec->setName('aaa');
		$spec->bindChildSpec(new Spec());
		$this->assertSame(false, $spec->isAnonymous());
	}
	
	public function testIsAnonymous_NameIsNotEmptyStringAndSpecHasNoChildren_ReturnsFalse() {
		$spec = new Spec();
		$spec->setName('aaa');
		$this->assertSame(false, $spec->isAnonymous());
	}
	
	public function testIsAnonymous_NameIsEmptyStringAndSpecHasChildren_ReturnsFalse() {
		$spec = new Spec();
		$spec->setName('');
		$spec->bindChildSpec(new Spec());
		$this->assertSame(false, $spec->isAnonymous());
	}
	
	public function testIsAnonymous_NameIsEmptyStringAndSpecHasNoChildren_ReturnsFalse() {
		$spec = new Spec();
		$spec->setName('');
		$this->assertSame(false, $spec->isAnonymous());
	}
	
	public function testIsAnonymous_NameIsZeroNumberAndSpecHasChildren_ReturnsFalse() {
		$spec = new Spec();
		$spec->setName(0);
		$spec->bindChildSpec(new Spec());
		$this->assertSame(false, $spec->isAnonymous());
	}
	
	public function testIsAnonymous_NameIsZeroNumberAndSpecHasNoChildren_ReturnsFalse() {
		$spec = new Spec();
		$spec->setName(0);
		$this->assertSame(false, $spec->isAnonymous());
	}
	
/**/
	
	public function testGetParentSpecs_ReturnsEmptyArrayByDefault() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->getParentSpecs());
	}
	
/**/
	
	public function testHasParentSpec_ReturnsTrueForBoundSpec() {
		$spec = new Spec();
		$parentSpec = new Spec();
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(true, $spec->hasParentSpec($parentSpec));
	}
	
	public function testHasParentSpec_ReturnsFalseForNotBoundSpec() {
		$spec = new Spec();
		$spec->bindParentSpec(new Spec());
		$this->assertSame(false, $spec->hasParentSpec(new Spec()));
	}
	
/**/
	
	public function testBindParentSpec_CreatesConnectionBetweenSpecs() {
		$spec = new Spec();
		$parentSpec = new Spec();
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(array($parentSpec), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec->getChildSpecs());
	}
	
	public function testBindParentSpec_DoesNotCreateConnectionBetweenAlreadyConnectedSpecs() {
		$spec = new Spec();
		$parentSpec = new Spec();
		
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(array($parentSpec), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec->getChildSpecs());
		
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(array($parentSpec), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec->getChildSpecs());
	}
	
	public function testBindParentSpec_CallOnRun_ThrowsExceptionAndDoesNotBindSpec() {
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) {
			$spec->bindParentSpec(new \spectrum\core\Spec());
		});
		$spec = new Spec();
		
		$this->assertThrowsException('\spectrum\Exception', 'Call of "\spectrum\core\Spec::bindParentSpec" method is forbidden on run', function() use($spec) {
			$spec->run();
		});
		
		$this->assertSame(array(), $spec->getParentSpecs());
	}
	
/**/
	
	public function testUnbindParentSpec_BreaksConnectionBetweenSpecs() {
		$spec = new Spec();
		$parentSpec = new Spec();
		
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(array($parentSpec), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec->getChildSpecs());
		
		$spec->unbindParentSpec($parentSpec);
		$this->assertSame(array(), $spec->getParentSpecs());
		$this->assertSame(array(), $parentSpec->getChildSpecs());
	}
	
	public function testUnbindParentSpec_DoesNotBreaksOtherConnections() {
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
	
	public function testUnbindParentSpec_ResetsArrayIndexes() {
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
	
	public function testUnbindParentSpec_ResetsArrayIndexesInUnboundSpec() {
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
	
	public function testUnbindParentSpec_NoConnectionBetweenSpecs_DoesNotTriggersError() {
		$spec = new Spec();
		$parentSpec = new Spec();
		
		$spec->unbindParentSpec($parentSpec);
		$this->assertSame(array(), $spec->getParentSpecs());
		$this->assertSame(array(), $parentSpec->getChildSpecs());
	}
	
	public function testUnbindParentSpec_CallOnRun_ThrowsExceptionAndDoesNotUnbindSpec() {
		$newSpec = new Spec();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use($newSpec) {
			$spec->unbindParentSpec($newSpec);
		});
		$spec = new Spec();
		$spec->bindParentSpec($newSpec);
		
		$this->assertThrowsException('\spectrum\Exception', 'Call of "\spectrum\core\Spec::unbindParentSpec" method is forbidden on run', function() use($spec) {
			$spec->run();
		});
		
		$this->assertSame(array($newSpec), $spec->getParentSpecs());
	}
	
/**/
	
	public function testUnbindAllParentSpecs_BreaksConnectionsWithAllParentSpecs() {
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
	
	public function testUnbindAllParentSpecs_ResetsArrayIndexes() {
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
	
	public function testUnbindAllParentSpecs_ResetsArrayIndexesInUnboundSpec() {
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
	
	public function testUnbindAllParentSpecs_CallOnRun_ThrowsExceptionAndDoesNotUnbindSpecs() {
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) {
			$spec->unbindAllParentSpecs();
		});
		
		$newSpec = new Spec();
		$spec = new Spec();
		$spec->bindParentSpec($newSpec);
		
		$this->assertThrowsException('\spectrum\Exception', 'Call of "\spectrum\core\Spec::unbindAllParentSpecs" method is forbidden on run', function() use($spec) {
			$spec->run();
		});
		
		$this->assertSame(array($newSpec), $spec->getParentSpecs());
	}
	
/**/
	
	public function testGetChildSpecs_ReturnsEmptyArrayByDefault() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->getChildSpecs());
	}
	
/**/
	
	public function testHasChildSpec_ReturnsTrueForBoundSpec() {
		$spec = new Spec();
		$childSpec = new Spec();
		$spec->bindChildSpec($childSpec);
		$this->assertSame(true, $spec->hasChildSpec($childSpec));
	}
	
	public function testHasChildSpec_ReturnsFalseForNotBoundSpec() {
		$spec = new Spec();
		$spec->bindChildSpec(new Spec());
		$this->assertSame(false, $spec->hasChildSpec(new Spec()));
	}
	
/**/
	
	public function testBindChildSpec_CreatesConnectionBetweenSpecs() {
		$spec = new Spec();
		$childSpec = new Spec();
		$spec->bindChildSpec($childSpec);
		$this->assertSame(array($childSpec), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec->getParentSpecs());
	}
	
	public function testBindChildSpec_DoesNotCreateConnectionBetweenAlreadyConnectedSpecs() {
		$spec = new Spec();
		$childSpec = new Spec();
		
		$spec->bindChildSpec($childSpec);
		$this->assertSame(array($childSpec), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec->getParentSpecs());
		
		$spec->bindChildSpec($childSpec);
		$this->assertSame(array($childSpec), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec->getParentSpecs());
	}
	
	public function testBindChildSpec_CallOnRun_ThrowsExceptionAndDoesNotBindSpec() {
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) {
			$spec->bindChildSpec(new \spectrum\core\Spec());
		});
		
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\Exception', 'Call of "\spectrum\core\Spec::bindChildSpec" method is forbidden on run', function() use($spec) {
			$spec->run();
		});
		
		$this->assertSame(array(), $spec->getChildSpecs());
	}
	
/**/
	
	public function testUnbindChildSpec_BreaksConnectionBetweenSpecs() {
		$spec = new Spec();
		$childSpec = new Spec();
		
		$spec->bindChildSpec($childSpec);
		$this->assertSame(array($childSpec), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec->getParentSpecs());
		
		$spec->unbindChildSpec($childSpec);
		$this->assertSame(array(), $spec->getChildSpecs());
		$this->assertSame(array(), $childSpec->getParentSpecs());
	}
	
	public function testUnbindChildSpec_DoesNotBreaksOtherConnections() {
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
	
	public function testUnbindChildSpec_ResetsArrayIndexes() {
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
	
	public function testUnbindChildSpec_ResetsArrayIndexesInUnboundSpec() {
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
	
	public function testUnbindChildSpec_NoConnectionBetweenSpecs_DoesNotTriggersError() {
		$spec = new Spec();
		$childSpec = new Spec();
		
		$spec->unbindChildSpec($childSpec);
		$this->assertSame(array(), $spec->getChildSpecs());
		$this->assertSame(array(), $childSpec->getParentSpecs());
	}

	public function testUnbindChildSpec_CallOnRun_ThrowsExceptionAndDoesNotUnbindSpec() {
		$newSpec = new Spec();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use($newSpec) {
			$spec->unbindChildSpec($newSpec);
		});
		$spec = new Spec();
		$spec->bindChildSpec($newSpec);
		
		$this->assertThrowsException('\spectrum\Exception', 'Call of "\spectrum\core\Spec::unbindChildSpec" method is forbidden on run', function() use($spec) {
			$spec->run();
		});
		
		$this->assertSame(array($newSpec), $spec->getChildSpecs());
	}
	
/**/
	
	public function testUnbindAllChildSpecs_BreaksConnectionsWithAllChildSpecs() {
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
	
	public function testUnbindAllChildSpecs_ResetsArrayIndexes() {
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
	
	public function testUnbindAllChildSpecs_ResetsArrayIndexesInUnboundSpec() {
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

	public function testUnbindAllChildSpecs_CallOnRun_ThrowsExceptionAndDoesNotUnbindSpecs() {
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) {
			$spec->unbindAllChildSpecs();
		});
		
		$newSpec = new Spec();
		$spec = new Spec();
		$spec->bindChildSpec($newSpec);
		
		$this->assertThrowsException('\spectrum\Exception', 'Call of "\spectrum\core\Spec::unbindAllChildSpecs" method is forbidden on run', function() use($spec) {
			$spec->run();
		});
		
		$this->assertSame(array($newSpec), $spec->getChildSpecs());
	}

/**/

	public function providerGetAncestorRootSpecs() {
		return array(
			array(
				'
					0    1
					 \  /
					 spec
				',
				array('0', '1'),
			),
			
			array(
				'
					0    1
					|    |
					2    3
					 \  /
					 spec
				',
				array('0', '1'),
			),
			
			array(
				'
					0
					|
					1    2
					|    |
					3    4
					 \  /
					 spec
				',
				array('0', '2'),
			),
			
			array(
				'
					0
					|
					1  2
					|  |
					3  4  5
					|  |  |
					6  7  8  9
					 \ |  | /
					   spec
				',
				array('0', '2', '5', '9'),
			),
		);
	}
	
	/**
	 * @dataProvider providerGetAncestorRootSpecs
	 */
	public function testGetAncestorRootSpecs_ReturnsAllRootSpecs($pattern, $expectedSpecKeys) {
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern($pattern);
		
		$expectedSpecs = array();
		foreach ($expectedSpecKeys as $specKey) {
			$expectedSpecs[] = $specs[$specKey];
		}
		
		$this->assertSame($expectedSpecs, $specs['spec']->getAncestorRootSpecs());
	}
	
	public function testGetAncestorRootSpecs_SpecHasNoParents_ReturnsEmptyArray() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->getAncestorRootSpecs());
		
		$spec = new Spec();
		$spec->bindChildSpec(new Spec());
		$this->assertSame(array(), $spec->getAncestorRootSpecs());
	}
	
/**/
	
	public function testGetDescendantEndingSpecs_ReturnsAllEndingSpecs() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec(endingSpec1)
			->Spec
			->->Spec(endingSpec2)
			->Spec
			->->Spec
			->->->Spec(endingSpec3)
			->->->Spec(endingSpec4)
		');
		$this->assertSame(array($specs['endingSpec1'], $specs['endingSpec2'], $specs['endingSpec3'], $specs['endingSpec4']), $specs[0]->getDescendantEndingSpecs());
	}
	
	public function testGetDescendantEndingSpecs_SpecHasNoChildren_ReturnsEmptyArray() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->getDescendantEndingSpecs());
		
		$spec = new Spec();
		$spec->bindParentSpec(new Spec());
		$this->assertSame(array(), $spec->getDescendantEndingSpecs());
	}
	
/**/
	
	public function testGetRunningParentSpec_ReturnsRunningParentSpec() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			->Spec
			->Spec
			Spec
		');
		
		$runningParentSpecs = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$runningParentSpecs) {
			if ($specs[2] === $spec) {
				$runningParentSpecs[] = $spec->getRunningParentSpec();
			}
		});
		
		$rootSpec = new Spec();
		$rootSpec->bindChildSpec($specs[0]);
		$rootSpec->bindChildSpec($specs[1]);
		$rootSpec->run();
		
		$this->assertSame(array($specs[0], $specs[1]), $runningParentSpecs);
	}
	
	public function testGetRunningParentSpec_NoRunningParentSpec_ReturnsNull() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame(null, $specs['aaa']->getRunningParentSpec());
	}
	
/**/

	public function testGetRunningAncestorSpecs_ReturnsRunningAncestorSpecs() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			->Spec
			->Spec
			Spec
		');
		
		$runningAncestorSpecs = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$runningAncestorSpecs) {
			if ($specs[2] === $spec) {
				$runningAncestorSpecs[] = $spec->getRunningAncestorSpecs();
			}
		});
		
		$rootSpec = new Spec();
		$rootSpec->bindChildSpec($specs[0]);
		$rootSpec->bindChildSpec($specs[1]);
		$rootSpec->run();
		
		$this->assertSame(array(
			array($specs[0], $rootSpec),
			array($specs[1], $rootSpec),
		), $runningAncestorSpecs);
	}
	
	public function testGetRunningAncestorSpecs_NoRunningParentSpec_ReturnsEmptyArray() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame(array(), $specs['aaa']->getRunningAncestorSpecs());
	}

/**/
	
	public function testGetRunningDescendantEndingSpec_ReturnsRunningEndingSpec() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
		');
		
		$runningEndingSpecs = array();
		config::registerEventListener('onSpecRunStart', function() use(&$specs, &$runningEndingSpecs) {
			$runningEndingSpecs[] = $specs[0]->getRunningDescendantEndingSpec();
		});
		
		$specs[0]->run();
		
		$this->assertSame(array(
			null,
			null,
			$specs[2],
		), $runningEndingSpecs);
	}
	
	public function testGetRunningDescendantEndingSpec_NoRunningChildren_ReturnsNull() {
		$spec = new Spec();
		$this->assertSame(null, $spec->getRunningDescendantEndingSpec());
	}
	
	public function testGetRunningDescendantEndingSpec_NoRunningChildrenAndSelfIsRunning_ReturnsNull() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$runningEndingSpecs = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$runningEndingSpecs) {
			if ($specs[0] === $spec) {
				$runningEndingSpecs[] = $spec->getRunningDescendantEndingSpec();
			}
		});
		
		$specs[0]->run();
		
		$this->assertSame(array(null), $runningEndingSpecs);
	}	
	
/**/
	
	public function testGetRunningChildSpec_ReturnsRunningChildSpec() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec(checkpoint)
		');
		
		$returnSpecs = array();
		config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$specs, &$returnSpecs) {
			if ($spec === $specs["checkpoint"]) {
				$returnSpecs[] = $specs[0]->getRunningChildSpec();
				$returnSpecs[] = $specs[1]->getRunningChildSpec();
				$returnSpecs[] = $specs["checkpoint"]->getRunningChildSpec();
			}
		});
		
		$specs[0]->run();
		
		$this->assertSame(array(
			$specs[1],
			$specs["checkpoint"],
			null,
		), $returnSpecs);
	}	
	
	public function testGetRunningChildSpec_NoRunningChildren_ReturnsNull() {
		$spec = new Spec();
		$this->assertSame(null, $spec->getRunningChildSpec());
	}
	
/**/

	public function providerGetSpecsByRunId_CorrectIds() {
		return array(
			array(
				'0',
				array(
					'r' => array('0'),
				),
			),
			
			// Single parent, level 2
			
			array(
				'
				0
				|
				1
				',
				array(
					'r_0' => array('0', '1'),
				),
			),
			
			array(
				'
				  0
				 / \
				1   2
				',
				array(
					'r_0' => array('0', '1'),
					'r_1' => array('0', '2'),
				),
			),
			
			array(
				'
				   0
				 / | \
				1  2  3
				',
				array(
					'r_1' => array('0', '2'),
				),
			),

			array(
				'
				  ___0___
				 / |  |  \
				1  2  3   4
				',
				array(
					'r_2' => array('0', '3'),
				),
			),
			
			// Single parent, level 3
			
			array(
				'
				0
				|
				1
				|
				2
				',
				array(
					'r_0_0' => array('0', '1', '2'),
				),
			),
			
			array(
				'
				  0
				 / \
				1   2
				.   |
				    3
				',
				array('r_1_0' => array('0', '2', '3')),
			),
			
			array(
				'
				   0
				 / | \
				1  2  3
				.  |
				   4
				',
				array(
					'r_1_0' => array('0', '2', '4'),
				),
			),
			
			array(
				'
				  _____0______
				 /     |      \
				1    __2____   3
				.   / |  |  \
				   4  5  6   7
				',
				array(
					'r_1_2' => array('0', '2', '6'),
					'r_1_0' => array('0', '2', '4'),
					'r_0' => array('0', '1'),
				),
			),
			
			// Single parent, level 4
			
			array(
				'
				  ____________0_____________
				 /            |             \
				1   __________2__________    3
				.  / |        |          \
				  4  5   _____6________   7
				  .  .  / |  |  |  |   \
				       8  9 10 11 12   13
				',
				array(
					'r_1_2_0' => array('0', '2', '6', '8'),
					'r_1_2_4' => array('0', '2', '6', '12'),
					'r_1_2_5' => array('0', '2', '6', '13'),
					'r_1_1' => array('0', '2', '5'),
				),
			),
			
			// Two parents
			
			array(
				'
				  0
				 / \
				1   2
				 \ /
				  3
				',
				array(
					'r_0_0' => array('0', '1', '3'),
					'r_1_0' => array('0', '2', '3'),
				),
			),
			
			array(
				'
				  __0__
				 / | | \
				1  2 3  4
				|  \ /  |
				5   6   7
				',
				array(
					'r_1_0' => array('0', '2', '6'),
					'r_2_0' => array('0', '3', '6'),
				),
			),
			
			array(
				'
				  0
				 / \
				1   2
				 \ /
				  3
				 / \
				4   5
				 \ /
				  6
				',
				array(
					'r_0_0_0_0' => array('0', '1', '3', '4', '6'),
					'r_0_0_1_0' => array('0', '1', '3', '5', '6'),
					'r_1_0_0_0' => array('0', '2', '3', '4', '6'),
					'r_1_0_1_0' => array('0', '2', '3', '5', '6'),
				),
			),
			
			// Three parents
			
			array(
				'
				   0
				 / | \
				1  2  3
				 \ | /
				   4
				',
				array(
					'r_0_0' => array('0', '1', '4'),
					'r_1_0' => array('0', '2', '4'),
					'r_2_0' => array('0', '3', '4'),
				),
			),
			
			array(
				'
				  ____0____
				 / |  |  | \
				1  2  3  4  5
				|   \ | /   |
				6     7     8
				',
				array(
					'r_1_0' => array('0', '2', '7'),
					'r_2_0' => array('0', '3', '7'),
					'r_3_0' => array('0', '4', '7'),
				),
			),
			
			array(
				'
				  _0_
				 / | \
				1  2  3
				 \ | /
				   4
				 / | \
				5  6  7
				 \ | /
				   8
				',
				array(
					'r_0_0_0_0' => array('0', '1', '4', '5', '8'),
					'r_0_0_1_0' => array('0', '1', '4', '6', '8'),
					'r_0_0_2_0' => array('0', '1', '4', '7', '8'),
					
					'r_1_0_0_0' => array('0', '2', '4', '5', '8'),
					'r_1_0_1_0' => array('0', '2', '4', '6', '8'),
					'r_1_0_2_0' => array('0', '2', '4', '7', '8'),
					
					'r_2_0_0_0' => array('0', '3', '4', '5', '8'),
					'r_2_0_1_0' => array('0', '3', '4', '6', '8'),
					'r_2_0_2_0' => array('0', '3', '4', '7', '8'),
				),
			),
		);
	}
	
	/**
	 * @dataProvider providerGetSpecsByRunId_CorrectIds
	 */
	public function testGetSpecsByRunId_ReturnsProperSpecs($pattern, array $expectedRunIdsAndSpecKeys) {
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern($pattern);
		
		foreach ($expectedRunIdsAndSpecKeys as $runId => $expectedSpecKeys) {
			$expectedSpecs = array();
			foreach ($expectedSpecKeys as $key) {
				$expectedSpecs[] = $specs[$key];
			}

			$this->assertSame($expectedSpecs, $specs['0']->getSpecsByRunId($runId));
		}
	}
	
	public function testGetSpecsByRunId_IgnoreInitialAndEndingSpaces() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern('
			  0
			 / \
			1   2
		');
		
		$this->assertSame(array($specs['0'], $specs['1']), $specs[0]->getSpecsByRunId("\r\n\t   r_0\r\n\t   "));
		$this->assertSame(array($specs['0'], $specs['2']), $specs[0]->getSpecsByRunId("\r\n\t   r_1\r\n\t   "));
	}
	
	public function testGetSpecsByRunId_SpecIsNotRoot_ThrowsException() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern('
			0
			|
			1
		');
		
		$this->assertThrowsException('\spectrum\Exception', 'Method "\spectrum\core\Spec::getSpecsByRunId" should be called from root spec only', function() use($specs){
			$specs['1']->getSpecsByRunId('r');
		});
	}
	
	public function providerGetSpecsByRunId_IncorrectIds() {
		return array(
			array('aaa'),
			array('r0'),
			array('0'),
			array('_0'),
			array('0_0'),
			array('_r_0'),
			array('r_0_'),
			array('_r_0_'),
		);
	}
	
	/**
	 * @dataProvider providerGetSpecsByRunId_IncorrectIds
	 */
	public function testGetSpecsByRunId_RunIdIsIncorrect_ThrowsException($runId) {
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\Exception', 'Incorrect run id "' . $runId . '" (id should be in format "r_<number>_<number>_...")', function() use($spec, $runId){
			$spec->getSpecsByRunId($runId);
		});
	}
	
	public function testGetSpecsByRunId_SpecWithDeclaredIndexIsNotExists_ThrowsException() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern('
			0
			|
			1
			|
			2
		');
		
		$this->assertThrowsException('\spectrum\Exception', 'Spec with index "1" on "2" position of run id "r_1_0" is not exists', function() use($specs){
			$specs['0']->getSpecsByRunId('r_1_0');
		});
	}
	
/**/
	
	public function testGetContextModifiers_ReturnsSameContextModifiersForEachCall() {
		$spec = new Spec();
		$contextModifiers = $spec->getContextModifiers();
		$this->assertTrue($contextModifiers instanceof ContextModifiers);
		$this->assertSame($contextModifiers, $spec->getContextModifiers());
	}
	
	public function testGetContextModifiers_UsesConfigForContextModifiersClassGetting() {
		$className = \spectrum\tests\_testware\tools::createClass('class ... extends \spectrum\core\ContextModifiers {}');
		config::setClassReplacement('\spectrum\core\ContextModifiers', $className);
		$spec = new Spec();
		$this->assertInstanceOf($className, $spec->getContextModifiers());
	}
	
/**/
	
	public function testGetData_ReturnsSameDataForEachCall() {
		$spec = new Spec();
		$data = $spec->getData();
		$this->assertTrue($data instanceof Data);
		$this->assertSame($data, $spec->getData());
	}
	
	public function testGetData_UsesConfigForDataClassGetting() {
		$className = \spectrum\tests\_testware\tools::createClass('class ... extends \spectrum\core\Data {}');
		config::setClassReplacement('\spectrum\core\Data', $className);
		$spec = new Spec();
		$this->assertInstanceOf($className, $spec->getData());
	}
	
/**/
	
	public function testGetErrorHandling_ReturnsSameErrorHandlingForEachCall() {
		$spec = new Spec();
		$errorHandling = $spec->getErrorHandling();
		$this->assertTrue($errorHandling instanceof ErrorHandling);
		$this->assertSame($errorHandling, $spec->getErrorHandling());
	}
	
	public function testGetErrorHandling_UsesConfigForErrorHandlingClassGetting() {
		$className = \spectrum\tests\_testware\tools::createClass('class ... extends \spectrum\core\ErrorHandling {}');
		config::setClassReplacement('\spectrum\core\ErrorHandling', $className);
		$spec = new Spec();
		$this->assertInstanceOf($className, $spec->getErrorHandling());
	}
	
/**/
	
	public function testGetMatchers_ReturnsSameMatchersForEachCall() {
		$spec = new Spec();
		$matchers = $spec->getMatchers();
		$this->assertTrue($matchers instanceof Matchers);
		$this->assertSame($matchers, $spec->getMatchers());
	}
	
	public function testGetMatchers_UsesConfigForMatchersClassGetting() {
		$className = \spectrum\tests\_testware\tools::createClass('class ... extends \spectrum\core\Matchers {}');
		config::setClassReplacement('\spectrum\core\Matchers', $className);
		$spec = new Spec();
		$this->assertInstanceOf($className, $spec->getMatchers());
	}
	
/**/
	
	public function testGetMessages_ReturnsSameMessagesForEachCall() {
		$spec = new Spec();
		$messages = $spec->getMessages();
		$this->assertTrue($messages instanceof Messages);
		$this->assertSame($messages, $spec->getMessages());
	}

	public function testGetMessages_UsesConfigForMessagesClassGetting() {
		$className = \spectrum\tests\_testware\tools::createClass('class ... extends \spectrum\core\Messages {}');
		config::setClassReplacement('\spectrum\core\Messages', $className);
		$spec = new Spec();
		$this->assertInstanceOf($className, $spec->getMessages());
	}
	
/**/
	
	public function testGetResultBuffer_ReturnsSameResultBufferForEachCall() {
		$spec = new Spec();
		$resultBuffer = $spec->getResultBuffer();
		$this->assertTrue($resultBuffer instanceof ResultBuffer);
		$this->assertSame($resultBuffer, $spec->getResultBuffer());
	}
	
	public function testGetResultBuffer_UsesConfigForResultBufferClassGetting() {
		$className = \spectrum\tests\_testware\tools::createClass('class ... extends \spectrum\core\ResultBuffer {}');
		config::setClassReplacement('\spectrum\core\ResultBuffer', $className);
		$spec = new Spec();
		$this->assertInstanceOf($className, $spec->getResultBuffer());
	}
	
/**/
	
	public function testGetExecutor_ReturnsSameExecutorForEachCall() {
		$spec = new Spec();
		$executor = $spec->getExecutor();
		$this->assertTrue($executor instanceof Executor);
		$this->assertSame($executor, $spec->getExecutor());
	}
	
	public function testGetExecutor_UsesConfigForExecutorClassGetting() {
		$className = \spectrum\tests\_testware\tools::createClass('class ... extends \spectrum\core\Executor {}');
		config::setClassReplacement('\spectrum\core\Executor', $className);
		$spec = new Spec();
		$this->assertInstanceOf($className, $spec->getExecutor());
	}

/**/
	
	public function providerGetRunId() {
		return array(
			array('
				spec
			', array(
				'r',
			)),
			
			// Single parent, level 2
			
			array('
				 0
				 |
				spec
			', array(
				'r_0',
			)),
			
			array('
				  0
				 / \
				1  spec
			', array(
				'r_1',
			)),
			
			array('
				   0
				 / | \
				1  2 spec
			', array(
				'r_2',
			)),
			
			array('
				  ___0___
				 / |  |  \
				1  2 spec 4
			', array(
				'r_2',
			)),
			
			// Single parent, level 3
			
			array('
				 0
				 |
				 1
				 |
				spec
			', array(
				'r_0_0',
			)),
			
			array('
				  0
				 / \
				1   2
				.   |
				   spec
			', array(
				'r_1_0',
			)),
			
			array('
				   0
				 / | \
				1  2  3
				.  |
				  spec
			', array(
				'r_1_0',
			)),
			
			array('
				  _____0______
				 /     |      \
				1    __2____   3
				.   / |  |  \
				   4  5 spec 7
			', array(
				'r_1_2',
			)),
			
			// Single parent, level 4
			
			array('
				  ____________0_____________
				 /            |             \
				1   __________2__________    3
				.  / |        |          \
				  4  5   _____6________   7
				  .  .  / |  |  |  |   \
				       8  9 10 11 spec 13
			', array(
				'r_1_2_4',
			)),
			
			// Two parents
			
			array('
				  0
				 / \
				1   2
				 \ /
				 spec
			', array(
				'r_0_0',
				'r_1_0',
			)),
			
			array('
				  __0__
				 / | | \
				1  2 3  4
				|  \ /  |
				5  spec 7
			', array(
				'r_1_0',
				'r_2_0',
			)),
			
			array('
				  0
				 / \
				1   2
				 \ /
				  3
				 / \
				4   5
				 \ /
				 spec
			', array(
				'r_0_0_0_0',
				'r_0_0_1_0',
				'r_1_0_0_0',
				'r_1_0_1_0',
			)),
			
			// Three parents
			
			array('
				   0
				 / | \
				1  2  3
				 \ | /
				 spec
			', array(
				'r_0_0',
				'r_1_0',
				'r_2_0',
			)),
			
			array('
				  ____0____
				 / |  |  | \
				1  2  3  4  5
				|   \ | /   |
				6   spec    8
			', array(
				'r_1_0',
				'r_2_0',
				'r_3_0',
			)),
			
			array('
				  _0_
				 / | \
				1  2  3
				 \ | /
				   4
				 / | \
				5  6  7
				 \ | /
				 spec
			', array(
				'r_0_0_0_0',
				'r_0_0_1_0',
				'r_0_0_2_0',
				
				'r_1_0_0_0',
				'r_1_0_1_0',
				'r_1_0_2_0',
				
				'r_2_0_0_0',
				'r_2_0_1_0',
				'r_2_0_2_0',
			)),
		);
	}
	
	/**
	 * @dataProvider providerGetRunId
	 */
	public function testGetRunId_SpecIsRunning_ReturnsUniqueId($pattern, $expectedRunIds) {
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern($pattern);
		$results = array();
		
		config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$specs, &$results) {
			if ($spec === $specs["spec"]) {
				$results[] = $spec->getRunId();
			}
		});

		$specs['spec']->run();
		$this->assertSame($expectedRunIds, $results);
	}
	
	public function testGetRunId_SpecIsNotRunning_ThrowsException() {
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\Exception', 'Call of "\spectrum\core\Spec::getRunId" method is available on run only', function() use($spec){
			$spec->getRunId();
		});
	}
	
/**/
	
	public function testIsRunning_ReturnsFalseByDefault() {
		$spec = new Spec();
		$this->assertSame(false, $spec->isRunning());
	}
	
/**/
	
	public function providerSpecsWithMoreThanOneRootAncestors() {
		return array(
			array('
				->Spec
				->Spec
				Spec(spec)
			'),
			array('
				->->Spec
				->->Spec
				->Spec
				Spec(spec)
			'),
			array('
				->->Spec
				->Spec
				->Spec
				Spec(spec)
			'),
		);
	}

	/**
	 * @dataProvider providerSpecsWithMoreThanOneRootAncestors
	 */
	public function testRun_SpecHasMoreThanOneRootAncestors_ThrowsException($specTreePattern) {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern($specTreePattern);
		$specs['spec']->setName('aaa');
		
		$this->assertThrowsException('\spectrum\Exception', 'Spec "aaa" has more than one root ancestors, but for run needs only one general root', function() use($specs) {
			$specs['spec']->run();
		});
	}
	
	public function testRun_SpecHasMoreThanOneRootAncestors_StopsRunByExceptionThrowing() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			->->Spec(caller)
			->Spec
			->Spec
			Spec(callee)
			->Spec
		');
		
		$calledSpecs = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$calledSpecs) {
			$calledSpecs[] = $spec;
			
			if ($specs["caller"] === $spec) {
				$specs["callee"]->run();
			}
		});

		$specs["callee"]->setName('aaa');

		$this->assertThrowsException('\spectrum\Exception', 'Spec "aaa" has more than one root ancestors, but for run needs only one general root', function() use(&$specs) {
			$specs["caller"]->run();
		});
		
		$this->assertSame(array(
			$specs["caller"],
		), $calledSpecs);
	}
	
/**/
	
	public function testRun_SpecIsAlreadyRunning_ThrowsException() {
		config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$exception) {
			try {
				$spec->run();
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		
		$spec = new Spec();
		$spec->setName('aaa');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\Exception', $exception);
		$this->assertSame('Spec "aaa" is already running', $exception->getMessage());
	}
	
	public function testRun_SpecIsAlreadyRunning_StopsRunByExceptionThrowing() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec(spec)
			->->Spec
		');
		
		$calledSpecs = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$calledSpecs) {
			$calledSpecs[] = $spec;
			
			if ($spec === $specs["spec"]) {
				$spec->run();
			}
		});
		
		$specs["spec"]->setName('aaa');

		$this->assertThrowsException('\spectrum\Exception', 'Spec "aaa" is already running', function() use($specs) {
			$specs[0]->run();
		});
		
		$this->assertSame(array(
			$specs[0],
			$specs["spec"],
		), $calledSpecs);
	}
	
/**/
	
	public function testRun_SpecHasAlreadyRunningSibling_ThrowsException() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec(caller)
			->Spec(callee)
		');
		
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$exception) {
			if ($specs["caller"] === $spec) {
				try {
					$specs["callee"]->run();
				} catch (\Exception $e) {
					$exception = $e;
				}
			}
		});
		
		$specs['callee']->setName('aaa');
		$specs[0]->run();
		
		$this->assertInstanceOf('\spectrum\Exception', $exception);
		$this->assertSame('Sibling spec of spec "aaa" is already running', $exception->getMessage());
	}
	
	public function testRun_SpecHasAlreadyRunningSibling_StopsRunByExceptionThrowing() {
		$calledSpecs = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$calledSpecs) {
			$calledSpecs[] = $spec;
			
			if ($specs["caller"] === $spec) {
				$specs["callee"]->run();
			}
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec(caller)
			->->Spec
			->Spec(callee)
		');
		
		$specs["callee"]->setName('aaa');

		$this->assertThrowsException('\spectrum\Exception', 'Sibling spec of spec "aaa" is already running', function() use(&$specs) {
			$specs[0]->run();
		});
		
		$this->assertSame(array(
			$specs[0],
			$specs["caller"],
		), $calledSpecs);
	}
	
/**/
	
	public function providerChildSpecRunWithoutRunningParent() {
		return array(
			
			/* Disable siblings of self */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec(callee)
				',
				array(true, false, true),
				array('checkpoint', 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec(callee)
				->Spec
				',
				array(true, true, false),
				array('checkpoint', 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec(callee)
				->Spec
				',
				array(true, false, true, false),
				array('checkpoint', 'callee'),
			),
			
			/* Disable siblings of parent */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec
				->->Spec(callee)
				',
				array(true, false, true, true),
				array('checkpoint', 2, 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->->Spec(callee)
				->Spec
				',
				array(true, true, true, false),
				array('checkpoint', 1, 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec
				->->Spec(callee)
				->Spec
				',
				array(true, false, true, true, false),
				array('checkpoint', 2, 'callee'),
			),
			
			/* Disable siblings of ancestor */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec
				->->Spec
				->->->Spec(callee)
				',
				array(true, false, true, true, true),
				array('checkpoint', 2, 3, 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->->Spec
				->->->Spec(callee)
				->Spec
				',
				array(true, true, true, true, false),
				array('checkpoint', 1, 2, 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec
				->->Spec
				->->->Spec(callee)
				->Spec
				',
				array(true, false, true, true, true, false),
				array('checkpoint', 2, 3, 'callee'),
			),
			
			/* Disable any quantity of spec */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec
				->Spec
				->Spec
				->->Spec
				->->Spec
				->->Spec
				->->Spec
				->->->Spec(callee)
				->->Spec
				->->Spec
				->->Spec
				->Spec
				->Spec
				->Spec
				',
				array(true, false, false, false, true, false, false, false, true, true, false, false, false, false, false, false),
				array('checkpoint', 4, 8, 'callee'),
			),
			
			/* Does not disable self and children of self */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec(callee)
				->->Spec
				->->Spec
				->->->Spec
				',
				array(true, false, true, true, true, true),
				array('checkpoint', 'callee', 3, 4, 5),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec
				->->Spec(callee)
				->->->Spec
				->->->Spec
				->->->->Spec
				',
				array(true, false, true, true, true, true, true),
				array('checkpoint', 2, 'callee', 4, 5, 6),
			),
			
			/* Does not disable ancestors of self */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->->Spec
				->->->Spec(callee)
				->->Spec
				',
				array(true, true, true, true, false),
				array('checkpoint', 1, 2, 'callee'),
			),
						
			/* Does not disable children of disabled specs if they are not siblings */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->->Spec
				->Spec(callee)
				',
				array(true, false, true, true),
				array('checkpoint', 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->->Spec
				->Spec
				->->Spec(callee)
				',
				array(true, false, true, true, true),
				array('checkpoint', 3, 'callee'),
			),
			
			/* Does not disable multiple parents */
			
			array(
				'
				Spec(checkpoint)
				->Spec(parent1)
				->->Spec
				->Spec(parent2)
				->->Spec
				->->Spec(callee)
				->Spec(parent3)
				->->Spec
				',
				array(true, true, false, true, false, true, true, false),
				array('checkpoint', 'parent1', 'callee', 'parent2', 'callee', 'parent3', 'callee'),
				array('parent1' => 'callee', 'parent3' => 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec(parent1)
				->Spec(parent2)
				->Spec(parent3)
				->->Spec
				->->Spec
				->->Spec(callee)
				',
				array(true, true, true, true, false, false, true),
				array('checkpoint', 'parent1', 'callee', 'parent2', 'callee', 'parent3', 'callee'),
				array('parent1' => array(4, 5, 'callee'), 'parent2' => array(4, 5, 'callee')),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec(ancestor1)
				->Spec(ancestor2)
				->Spec(ancestor3)
				->->Spec
				->->Spec
				->->Spec
				->->Spec(parent1)
				->->Spec(parent2)
				->->Spec(parent3)
				->->->Spec
				->->->Spec
				->->->Spec(callee)
				',
				array(true, true, true, true, false, false, false, true, true, true, false, false, true),
				array(
					'checkpoint',
					'ancestor1', 'parent1', 'callee', 'parent2', 'callee', 'parent3', 'callee',
					'ancestor2', 'parent1', 'callee', 'parent2', 'callee', 'parent3', 'callee',
					'ancestor3', 'parent1', 'callee', 'parent2', 'callee', 'parent3', 'callee',
				),
				array(
					'ancestor1' => array(4, 5, 6, 'parent1', 'parent2', 'parent3'),
					'ancestor2' => array(4, 5, 6, 'parent1', 'parent2', 'parent3'),
					'parent1' => array(10, 11, 'callee'),
					'parent2' => array(10, 11, 'callee'),
				),
			),
		);
	}

	/**
	 * @dataProvider providerChildSpecRunWithoutRunningParent
	 */
	public function testRun_ChildSpecRunWithoutRunningParent_DisablesSiblingSpecsUpToRootAndRunRootSpec($specTreePattern, $specStates, $calledSpecs, $specBindings = array()) {
		$specStates = array();
		$calledSpecs = array();
		
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$specStates, &$calledSpecs) {
			if ($spec === $specs["checkpoint"]) {
				foreach ($specs as $spec) {
					$specStates[] = $spec->isEnabled();
				}
			}
			
			$calledSpecs[] = array_search($spec, $specs, true);
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern($specTreePattern, $specBindings);
		$specs["callee"]->run();
		
		$this->assertSame($specStates, $specStates);
		$this->assertSame($calledSpecs, $calledSpecs);
	}
	
	/**
	 * @dataProvider providerChildSpecRunWithoutRunningParent
	 */
	public function testRun_ChildSpecRunWithoutRunningParent_EnablesDisabledSpecsAfterRun($specTreePattern, $specStates, $calledSpecs, $specBindings = array()) {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern($specTreePattern, $specBindings);
		$specs["callee"]->run();
		
		foreach ($specs as $spec) {
			$this->assertSame(true, $spec->isEnabled());
		}
	}
	
	public function providerDisabledChildSpecs() {
		return array(
			array('
				Spec
				->Spec(callee)
				->Spec(disabled)
			'),
			
			array('
				Spec
				->Spec
				->->Spec(callee)
				->->Spec(disabled)
			'),
		);
	}

	/**
	 * @dataProvider providerDisabledChildSpecs
	 */
	public function testRun_ChildSpecRunWithoutRunningParent_DoesNotEnableUserDisabledSpecsAfterRun($specTreePattern) {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern($specTreePattern);
		$specs["disabled"]->disable();
		$specs["callee"]->run();
		
		$this->assertSame(false, $specs["disabled"]->isEnabled());
	}
	
	public function testRun_ChildSpecRunWithoutRunningParent_ReturnsRootSpecRunResult() {
		$resultBufferClassName = \spectrum\tests\_testware\tools::createClass('
			class ... extends \spectrum\core\ResultBuffer {
				public function getTotalResult() {
					if ($this->getOwnerSpec() === \spectrum\tests\_testware\tools::$temp["specs"][0]) {
						return true;
					} else {
						return false;
					}
				}
			}
		');
		
		config::setClassReplacement('\spectrum\core\ResultBuffer', $resultBufferClassName);
		
		\spectrum\tests\_testware\tools::$temp["specs"] = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
		');
		
		$this->assertSame(true, \spectrum\tests\_testware\tools::$temp["specs"][2]->run());
	}
	
	public function testRun_ChildSpecRunWithoutRunningParent_RootIsAlreadyRunning_ThrowsException() {
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$exception) {
			if ($specs["caller"] === $spec) {
				try {
					$specs["callee"]->run();
				} catch (\Exception $e) {
					$exception = $e;
				}
			}
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec(caller)
			->Spec
			->->Spec
			->->->Spec(callee)
		');
		
		$specs["callee"]->setName('aaa');
		$specs["caller"]->run();

		$this->assertInstanceOf('\spectrum\Exception', $exception);
		$this->assertSame('Root spec of spec "aaa" is already running', $exception->getMessage());
	}
	
	public function testRun_ChildSpecRunWithoutRunningParent_RootIsAlreadyRunning_StopsRunByExceptionThrowing() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec(caller)
			->Spec
			->->Spec
			->->->Spec(callee)
		');
		
		$calledSpecs = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$calledSpecs) {
			$calledSpecs[] = $spec;
			
			if ($specs["caller"] === $spec) {
				$specs["callee"]->run();
			}
		});
		
		$specs["callee"]->setName('aaa');

		$this->assertThrowsException('\spectrum\Exception', 'Root spec of spec "aaa" is already running', function() use(&$specs) {
			$specs["caller"]->run();
		});
		
		$this->assertSame(array($specs["caller"]), $calledSpecs);
	}
	
/**/
	
	public function testRun_RootSpecRun_EnablesRunningFlagDuringRun() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
		');
		
		$isRunningCallResults = array();
		config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$specs, &$isRunningCallResults) {
			if ($spec === $specs[2]) {
				$isRunningCallResults[] = $specs[0]->isRunning();
				$isRunningCallResults[] = $specs[1]->isRunning();
				$isRunningCallResults[] = $specs[2]->isRunning();
			}
		});
		
		$this->assertSame(false, $specs[0]->isRunning());
		$this->assertSame(false, $specs[1]->isRunning());
		$this->assertSame(false, $specs[2]->isRunning());
		
		$specs[0]->run();
		
		$this->assertSame(false, $specs[0]->isRunning());
		$this->assertSame(false, $specs[1]->isRunning());
		$this->assertSame(false, $specs[2]->isRunning());
		
		$this->assertSame(array(true, true, true), $isRunningCallResults);
	}
	
	public function testRun_RootSpecRun_DisablesRunningFlagAfterEachChildSpecRun() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
			->->Spec
		');
		
		$isRunningCallResults = array();
		config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$specs, &$isRunningCallResults) {
			if ($spec === $specs[4]) {
				$isRunningCallResults[] = $specs[0]->isRunning();
				$isRunningCallResults[] = $specs[1]->isRunning();
				$isRunningCallResults[] = $specs[2]->isRunning();
				$isRunningCallResults[] = $specs[3]->isRunning();
				$isRunningCallResults[] = $specs[4]->isRunning();
			}
		});
		
		$specs[0]->run();
		$this->assertSame(array(true, false, false, true, true), $isRunningCallResults);
	}

	public function testRun_RootSpecRun_RunsChildSpecsForNotEndingSpecsSequentially() {
		$runSpecs = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
			->Spec
			->->Spec
			->Spec
			->->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[1], $specs[2], $specs[3], $specs[4], $specs[5], $specs[6]), $runSpecs);
	}
	
	public function testRun_RootSpecRun_RunsEnabledSpecsOnly() {
		$runSpecs = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
			->->Spec
			->Spec
		');
		
		$specs[1]->disable();
		$specs[2]->disable();
		$specs[5]->disable();
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[3], $specs[4]), $runSpecs);
	}
	
	public function testRun_RootSpecRun_DoesNotRunChildrenOfDisabledSpecs() {
		$runSpecs = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
			->->Spec
			->Spec
		');
		
		$specs[1]->disable();
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[4]), $runSpecs);
	}
	
	public function testRun_RootSpecRun_ReturnsResultBufferTotalResult() {
		$resultBufferClassName = \spectrum\tests\_testware\tools::createClass('
			class ... extends \spectrum\core\ResultBuffer {
				public function getTotalResult() {
					return \spectrum\tests\_testware\tools::$temp["totalResult"];
				}
			}
		');
		
		config::setClassReplacement('\spectrum\core\ResultBuffer', $resultBufferClassName);
		
		$spec = new Spec();
		
		\spectrum\tests\_testware\tools::$temp["totalResult"] = true;
		$this->assertSame(true, $spec->run());
		
		\spectrum\tests\_testware\tools::$temp["totalResult"] = false;
		$this->assertSame(false, $spec->run());
		
		\spectrum\tests\_testware\tools::$temp["totalResult"] = null;
		$this->assertSame(null, $spec->run());
	}
	
/**/
	
	public function testRun_RootSpecRun_Data_UnsetLinkToDataBeforeRun() {
		$spec = new Spec();
		$data1 = $spec->getData();
		$spec->getExecutor()->setFunction(function() use(&$spec, &$data2) {
			$data2 = $spec->getData();
		});
		$spec->run();
		
		$this->assertNotSame($data1, $data2);
	}
	
	public function testRun_RootSpecRun_Data_UnsetLinkToDataAfterRun() {
		$spec = new Spec();
		$spec->getExecutor()->setFunction(function() use(&$spec, &$data) {
			$data = $spec->getData();
		});
		$spec->run();
		
		$this->assertNotSame($data, $spec->getData());
	}
	
	public function testRun_RootSpecRun_Data_DoesNotClearDataContentsAfterRun() {
		$spec = new Spec();
		$spec->getExecutor()->setFunction(function() use(&$spec, &$data) {
			$data = $spec->getData();
			$data->aaa = 111;
		});
		$spec->run();
		
		$this->assertSame(111, $data->aaa);
	}
	
/**/
	
	public function testRun_RootSpecRun_ErrorHandling_GetsPhpErrorDetailsClassFromConfig() {
		$phpErrorDetailsClassName = \spectrum\tests\_testware\tools::createClass('class ... extends \spectrum\core\details\PhpError {}');
		config::setClassReplacement('\spectrum\core\details\PhpError', $phpErrorDetailsClassName);

		error_reporting(E_USER_WARNING);
		
		$spec = new Spec();
		$spec->getErrorHandling()->setCatchPhpErrors(-1);
		$spec->getExecutor()->setFunction(function() use(&$spec, &$resultBuffer) {
			$resultBuffer = $spec->getResultBuffer();
			trigger_error("aaa", E_USER_NOTICE);
		});
		$spec->run();
		
		$results = $resultBuffer->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf($phpErrorDetailsClassName, $results[0]['details']);
		$this->assertSame('aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_USER_NOTICE, $results[0]['details']->getErrorLevel());
	}
	
	public function testRun_RootSpecRun_ErrorHandling_GetsErrorTypeFromAncestorOrSelf() {
		$resultBuffers = array();
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
			->->Spec
		', array(2 => 4));
		
		$specs[1]->getErrorHandling()->setCatchPhpErrors(E_USER_NOTICE);
		$specs[2]->getErrorHandling()->setCatchPhpErrors(E_USER_WARNING);
		$specs[3]->getErrorHandling()->setCatchPhpErrors(E_USER_ERROR);
		$specs[0]->getExecutor()->setFunction(function() use(&$specs, &$resultBuffers) {
			$resultBuffers[] = $specs[0]->getRunningDescendantEndingSpec()->getResultBuffer();
			trigger_error("aaa", E_USER_NOTICE);
			trigger_error("bbb", E_USER_WARNING);
			trigger_error("ccc", E_USER_ERROR);
		});
		$specs[0]->run();
		
		$this->assertSame(3, count($resultBuffers));

		$results = $resultBuffers[0]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(E_USER_NOTICE, $results[0]['details']->getErrorLevel());
		
		$results = $resultBuffers[1]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(E_USER_WARNING, $results[0]['details']->getErrorLevel());
		
		$results = $resultBuffers[2]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(E_USER_ERROR, $results[0]['details']->getErrorLevel());
	}
	
	public function testRun_RootSpecRun_ErrorHandling_TakesInAccountDefinedOnRunErrorReportingValue() {
		$spec = new Spec();
		$spec->getErrorHandling()->setCatchPhpErrors(-1);
		$spec->getExecutor()->setFunction(function() use(&$spec, &$resultBuffer) {
			$resultBuffer = $spec->getResultBuffer();
			error_reporting(E_USER_WARNING);
			trigger_error("aaa", E_USER_NOTICE);
		});
		$spec->run();
		
		$this->assertSame(array(), $resultBuffer->getResults());
	}
	
	public function testRun_RootSpecRun_ErrorHandling_DoesNotTakeInAccountDefinedBeforeRunErrorReportingValue() {
		error_reporting(E_USER_WARNING);
		
		$spec = new Spec();
		$spec->getErrorHandling()->setCatchPhpErrors(-1);
		$spec->getExecutor()->setFunction(function() use(&$spec, &$resultBuffer) {
			$resultBuffer = $spec->getResultBuffer();
			trigger_error("aaa", E_USER_NOTICE);
		});
		$spec->run();
		
		$results = $resultBuffer->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_USER_NOTICE, $results[0]['details']->getErrorLevel());
	}
	
	public function testRun_RootSpecRun_ErrorHandling_RestoreErrorReportingValueAfterRun() {
		error_reporting(E_NOTICE);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(E_NOTICE, error_reporting());
	}
	
	public function testRun_RootSpecRun_ErrorHandling_RemovesErrorHandlerAfterRun() {
		$errorHandler = function($errorSeverity, $errorMessage){};
		set_error_handler($errorHandler);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame($errorHandler, \spectrum\tests\_testware\tools::getLastErrorHandler());
		
		restore_error_handler();
	}
	
	public function testRun_RootSpecRun_ErrorHandling_RemovesAlienErrorHandlersAddedOnExecute() {
		$errorHandler = function($errorSeverity, $errorMessage){};
		set_error_handler($errorHandler);
		
		$spec = new Spec();
		$spec->getExecutor()->setFunction(function() {
			set_error_handler(function($errorSeverity, $errorMessage){});
			set_error_handler(function($errorSeverity, $errorMessage){});
			set_error_handler(function($errorSeverity, $errorMessage){});
		});
		$spec->run();
		
		$this->assertSame($errorHandler, \spectrum\tests\_testware\tools::getLastErrorHandler());
		
		restore_error_handler();
	}
	
	public function testRun_RootSpecRun_ErrorHandling_CatchesPhpErrorsFromContextModifiers() {
		$spec = new Spec();
		$spec->getErrorHandling()->setCatchPhpErrors(-1);
		$spec->getContextModifiers()->add(function(){ trigger_error("aaa", E_USER_NOTICE); }, 'before');
		$spec->getContextModifiers()->add(function(){ trigger_error("bbb", E_USER_WARNING); }, 'after');
		$spec->getExecutor()->setFunction(function() use(&$spec, &$resultBuffer) {
			$resultBuffer = $spec->getResultBuffer();
		});
		$spec->run();
		
		$results = $resultBuffer->getResults();
		$this->assertSame(2, count($results));
		
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_USER_NOTICE, $results[0]['details']->getErrorLevel());
		
		$this->assertSame(false, $results[1]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[1]['details']);
		$this->assertSame('bbb', $results[1]['details']->getErrorMessage());
		$this->assertSame(E_USER_WARNING, $results[1]['details']->getErrorLevel());
	}
	
	public function testRun_RootSpecRun_ErrorHandling_CatchesPhpErrorsFromTest() {
		\spectrum\tests\_testware\tools::$temp["resultBuffer"] = null;
		
		$spec = new Spec();
		$spec->getErrorHandling()->setCatchPhpErrors(-1);
		$spec->getExecutor()->setFunction(function() use(&$spec, &$resultBuffer) {
			$resultBuffer = $spec->getResultBuffer();
			trigger_error("aaa", E_USER_NOTICE);
		});
		$spec->run();
		
		$results = $resultBuffer->getResults();
		$this->assertSame(1, count($results));
		
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_USER_NOTICE, $results[0]['details']->getErrorLevel());
	}
	
	public function testRun_RootSpecRun_ErrorHandling_ErrorHandlerWasRemovedOnExecute_AddsFalseToResultBufferAndDoesNotRemoveOtherErrorHandlers() {
		$errorHandler1 = function($errorSeverity, $errorMessage){};
		set_error_handler($errorHandler1);
		
		$errorHandler2 = function($errorSeverity, $errorMessage){};
		set_error_handler($errorHandler2);
		
		$errorHandler3 = function($errorSeverity, $errorMessage){};
		set_error_handler($errorHandler3);
		
		$spec = new Spec();
		$spec->getExecutor()->setFunction(function() use(&$spec, &$resultBuffer) {
			$resultBuffer = $spec->getResultBuffer();
			restore_error_handler();
		});
		$spec->run();
		
		$this->assertSame(array(
			array('result' => false, 'details' => 'Spectrum error handler was removed'),
		), $resultBuffer->getResults());
		
		$this->assertSame($errorHandler3, \spectrum\tests\_testware\tools::getLastErrorHandler());
		restore_error_handler();
		
		$this->assertSame($errorHandler2, \spectrum\tests\_testware\tools::getLastErrorHandler());
		restore_error_handler();
		
		$this->assertSame($errorHandler1, \spectrum\tests\_testware\tools::getLastErrorHandler());
		restore_error_handler();
	}
		
	public function testRun_RootSpecRun_ErrorHandling_ErrorTypeIsIncludeTriggeredErrorType_CatchesPhpErrorsAndAddsFalseResultToResultBuffer() {
		$resultBuffers = array();
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
			->Spec
			->->Spec
		', array(1 => 5, 2 => 5, 3 => 5));
		
		$specs[1]->getErrorHandling()->setCatchPhpErrors(E_NOTICE);
		$specs[2]->getErrorHandling()->setCatchPhpErrors(E_USER_WARNING);
		$specs[3]->getErrorHandling()->setCatchPhpErrors(E_ALL);
		$specs[4]->getErrorHandling()->setCatchPhpErrors(-1);
		$specs[0]->getExecutor()->setFunction(function() use(&$specs, &$resultBuffers) {
			$resultBuffers[] = $specs[0]->getRunningDescendantEndingSpec()->getResultBuffer();
			trim($aaa);
			trigger_error("bbb", E_USER_WARNING);
		});
		$specs[0]->run();
		
		$this->assertSame(4, count($resultBuffers));
		
		$results = $resultBuffers[0]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('Undefined variable: aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_NOTICE, $results[0]['details']->getErrorLevel());
		
		$results = $resultBuffers[1]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('bbb', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_USER_WARNING, $results[0]['details']->getErrorLevel());
		
		$results = $resultBuffers[2]->getResults();
		$this->assertSame(2, count($results));
		
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('Undefined variable: aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_NOTICE, $results[0]['details']->getErrorLevel());
		
		$this->assertSame(false, $results[1]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[1]['details']);
		$this->assertSame('bbb', $results[1]['details']->getErrorMessage());
		$this->assertSame(E_USER_WARNING, $results[1]['details']->getErrorLevel());
		
		$results = $resultBuffers[3]->getResults();
		$this->assertSame(2, count($results));
		
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[0]['details']);
		$this->assertSame('Undefined variable: aaa', $results[0]['details']->getErrorMessage());
		$this->assertSame(E_NOTICE, $results[0]['details']->getErrorLevel());
		
		$this->assertSame(false, $results[1]['result']);
		$this->assertInstanceOf('\spectrum\core\details\PhpError', $results[1]['details']);
		$this->assertSame('bbb', $results[1]['details']->getErrorMessage());
		$this->assertSame(E_USER_WARNING, $results[1]['details']->getErrorLevel());
	}
	
	public function testRun_RootSpecRun_ErrorHandling_ErrorTypeIsNotIncludeTriggeredErrorType_CatchesPhpErrorsAndDoesNotAddResultsToResultBuffer() {
		$spec = new Spec();
		$spec->getErrorHandling()->setCatchPhpErrors(0);
		$spec->getExecutor()->setFunction(function() use(&$spec, &$resultBuffer) {
			$resultBuffer = $spec->getResultBuffer();
			trigger_error("aaa", E_USER_WARNING);
		});
		$spec->run();
		
		$this->assertSame(array(), $resultBuffer->getResults());
	}
	
	public function testRun_RootSpecRun_ErrorHandling_ExpressionWithErrorControlOperator_CatchesPhpErrorsAndDoesNotAddResultsToResultBuffer() {
		$spec = new Spec();
		$spec->getErrorHandling()->setCatchPhpErrors(-1);
		$spec->getExecutor()->setFunction(function() use(&$spec, &$resultBuffer) {
			$resultBuffer = $spec->getResultBuffer();
			@trim($aaa);
			@trigger_error("aaa");
		});
		$spec->run();
		
		$this->assertSame(array(), $resultBuffer->getResults());
	}
	
	public function testRun_RootSpecRun_ErrorHandling_BreakOnFirstPhpErrorIsEnabled_BreaksExecutionOnFirstPhpError() {
		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstPhpError(true);
		$spec->getErrorHandling()->setCatchPhpErrors(-1);
		$spec->getExecutor()->setFunction(function() use(&$isExecuted) {
			trigger_error("aaa");
			$isExecuted = true;
		});
		$spec->run();
		
		$this->assertSame(null, $isExecuted);
	}
	
	public function testRun_RootSpecRun_ErrorHandling_BreakOnFirstPhpErrorIsEnabled_GetsValueFromAncestorOrSelf() {
		$callCount = -1;
		$isExecuted = array();
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
			->Spec
			->->Spec
		', array(3 => 5));
		
		$specs[0]->getErrorHandling()->setCatchPhpErrors(-1);
		$specs[1]->getErrorHandling()->setBreakOnFirstPhpError(true);
		$specs[2]->getErrorHandling()->setBreakOnFirstPhpError(false);
		$specs[3]->getErrorHandling()->setBreakOnFirstPhpError(true);
		$specs[4]->getErrorHandling()->setBreakOnFirstPhpError(false);
		$specs[0]->getExecutor()->setFunction(function() use(&$callCount, &$isExecuted) {
			$callCount++;
			
			$isExecuted[$callCount][] = 1;
			trigger_error("aaa");
			$isExecuted[$callCount][] = 2;
		});
		$specs[0]->run();
		
		$this->assertSame(array(
			array(1),
			array(1, 2),
			array(1),
			array(1, 2),
		), $isExecuted);
	}
	
/**/
	
	public function testRun_RootSpecRun_Messages_UnsetLinkToMessagesBeforeRun() {
		$spec = new Spec();
		$messages1 = $spec->getMessages();
		$spec->getExecutor()->setFunction(function() use(&$spec, &$messages2) {
			$messages2 = $spec->getMessages();
		});
		$spec->run();
		
		$this->assertNotSame($messages1, $messages2);
	}
	
	public function testRun_RootSpecRun_Messages_UnsetLinkToMessagesAfterRun() {
		$spec = new Spec();
		$spec->getExecutor()->setFunction(function() use(&$spec, &$messages) {
			$messages = $spec->getMessages();
		});
		$spec->run();
		
		$this->assertNotSame($messages, $spec->getMessages());
	}
	
	public function testRun_RootSpecRun_Messages_DoesNotClearMessagesContentsAfterRun() {
		$spec = new Spec();
		$spec->getExecutor()->setFunction(function() use(&$spec, &$messages) {
			$messages = $spec->getMessages();
			$messages->add('aaa');
		});
		$spec->run();
		
		$this->assertSame(array('aaa'), $messages->getAll());
	}

/**/
	
	public function testRun_RootSpecRun_ResultBuffer_CreatesNewResultBufferWithProperLinkToOwnerSpecForEachSpec() {
		\spectrum\tests\_testware\tools::$temp["resultBuffers"] = array();
		
		$resultBufferClassName = \spectrum\tests\_testware\tools::createClass('
			class ... extends \spectrum\core\ResultBuffer {
				public function __construct(\spectrum\core\SpecInterface $ownerSpec) {
					\spectrum\tests\_testware\tools::$temp["resultBuffers"][] = array(
						"resultBuffer" => $this,
						"ownerSpec" => $ownerSpec,
					);
					
					return call_user_func_array("parent::__construct", func_get_args());
				}
			}
		');
		
		config::setClassReplacement('\spectrum\core\ResultBuffer', $resultBufferClassName);
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->->Spec
			->->Spec
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(childSpec1)
			->->Spec(childSpec2)
		');
		
		$specs['parent1']->bindChildSpec($specs['childSpec1']);
		$specs['parent1']->bindChildSpec($specs['childSpec2']);
		$specs[0]->run();
		
		$this->assertSame(11, count(\spectrum\tests\_testware\tools::$temp["resultBuffers"]));
		
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\_testware\tools::$temp["resultBuffers"][0]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\_testware\tools::$temp["resultBuffers"][1]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\_testware\tools::$temp["resultBuffers"][2]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\_testware\tools::$temp["resultBuffers"][3]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\_testware\tools::$temp["resultBuffers"][4]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\_testware\tools::$temp["resultBuffers"][5]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\_testware\tools::$temp["resultBuffers"][6]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\_testware\tools::$temp["resultBuffers"][7]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\_testware\tools::$temp["resultBuffers"][8]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\_testware\tools::$temp["resultBuffers"][9]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\_testware\tools::$temp["resultBuffers"][10]["resultBuffer"]);
		
		$this->assertSame($specs[0], \spectrum\tests\_testware\tools::$temp["resultBuffers"][0]["ownerSpec"]);
		$this->assertSame($specs[1], \spectrum\tests\_testware\tools::$temp["resultBuffers"][1]["ownerSpec"]);
		$this->assertSame($specs[2], \spectrum\tests\_testware\tools::$temp["resultBuffers"][2]["ownerSpec"]);
		$this->assertSame($specs[3], \spectrum\tests\_testware\tools::$temp["resultBuffers"][3]["ownerSpec"]);
		$this->assertSame($specs[4], \spectrum\tests\_testware\tools::$temp["resultBuffers"][4]["ownerSpec"]);
		$this->assertSame($specs['parent1'], \spectrum\tests\_testware\tools::$temp["resultBuffers"][5]["ownerSpec"]);
		$this->assertSame($specs['childSpec1'], \spectrum\tests\_testware\tools::$temp["resultBuffers"][6]["ownerSpec"]);
		$this->assertSame($specs['childSpec2'], \spectrum\tests\_testware\tools::$temp["resultBuffers"][7]["ownerSpec"]);
		$this->assertSame($specs['parent2'], \spectrum\tests\_testware\tools::$temp["resultBuffers"][8]["ownerSpec"]);
		$this->assertSame($specs['childSpec1'], \spectrum\tests\_testware\tools::$temp["resultBuffers"][9]["ownerSpec"]);
		$this->assertSame($specs['childSpec2'], \spectrum\tests\_testware\tools::$temp["resultBuffers"][10]["ownerSpec"]);
		
		$this->assertSame($specs[0], \spectrum\tests\_testware\tools::$temp["resultBuffers"][0]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs[1], \spectrum\tests\_testware\tools::$temp["resultBuffers"][1]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs[2], \spectrum\tests\_testware\tools::$temp["resultBuffers"][2]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs[3], \spectrum\tests\_testware\tools::$temp["resultBuffers"][3]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs[4], \spectrum\tests\_testware\tools::$temp["resultBuffers"][4]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs['parent1'], \spectrum\tests\_testware\tools::$temp["resultBuffers"][5]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs['childSpec1'], \spectrum\tests\_testware\tools::$temp["resultBuffers"][6]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs['childSpec2'], \spectrum\tests\_testware\tools::$temp["resultBuffers"][7]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs['parent2'], \spectrum\tests\_testware\tools::$temp["resultBuffers"][8]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs['childSpec1'], \spectrum\tests\_testware\tools::$temp["resultBuffers"][9]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs['childSpec2'], \spectrum\tests\_testware\tools::$temp["resultBuffers"][10]["resultBuffer"]->getOwnerSpec());
		
		foreach (\spectrum\tests\_testware\tools::$temp["resultBuffers"] as $key => $val) {
			foreach (\spectrum\tests\_testware\tools::$temp["resultBuffers"] as $key2 => $val2) {
				if ($key != $key2) {
					$this->assertNotSame($val2["resultBuffer"], $val["resultBuffer"]);
				}
			}
		}
	}
	
	public function testRun_RootSpecRun_ResultBuffer_CreatesNewResultBufferForEachRun() {
		\spectrum\tests\_testware\tools::$temp["resultBuffers"] = array();
		
		$resultBufferClassName = \spectrum\tests\_testware\tools::createClass('
			class ... implements \spectrum\core\ResultBufferInterface {
				public function __construct(\spectrum\core\SpecInterface $ownerSpec) {
					\spectrum\tests\_testware\tools::$temp["resultBuffers"][] = $this;
				}
			
				public function getOwnerSpec(){}
				
				public function addResult($result, $details = null){}
				public function getResults(){}
				public function getTotalResult(){}
			}
		');
		
		config::setClassReplacement('\spectrum\core\ResultBuffer', $resultBufferClassName);
		
		$spec = new Spec();
		$spec->run();
		$spec->run();
		$spec->run();
		
		$this->assertSame(3, count(\spectrum\tests\_testware\tools::$temp["resultBuffers"]));

		$this->assertNotSame(\spectrum\tests\_testware\tools::$temp["resultBuffers"][0], \spectrum\tests\_testware\tools::$temp["resultBuffers"][1]);
		$this->assertNotSame(\spectrum\tests\_testware\tools::$temp["resultBuffers"][0], \spectrum\tests\_testware\tools::$temp["resultBuffers"][2]);
		
		$this->assertNotSame(\spectrum\tests\_testware\tools::$temp["resultBuffers"][1], \spectrum\tests\_testware\tools::$temp["resultBuffers"][0]);
		$this->assertNotSame(\spectrum\tests\_testware\tools::$temp["resultBuffers"][1], \spectrum\tests\_testware\tools::$temp["resultBuffers"][2]);
		
		$this->assertNotSame(\spectrum\tests\_testware\tools::$temp["resultBuffers"][2], \spectrum\tests\_testware\tools::$temp["resultBuffers"][0]);
		$this->assertNotSame(\spectrum\tests\_testware\tools::$temp["resultBuffers"][2], \spectrum\tests\_testware\tools::$temp["resultBuffers"][1]);
	}
	
	public function testRun_RootSpecRun_ResultBuffer_UnsetLinkToResultBufferBeforeRun() {
		$spec = new Spec();
		$resultBuffer1 = $spec->getResultBuffer();
		$spec->getExecutor()->setFunction(function() use(&$spec, &$resultBuffer2) {
			$resultBuffer2 = $spec->getResultBuffer();
		});
		$spec->run();
		
		$this->assertNotSame($resultBuffer1, $resultBuffer2);
	}
	
	public function testRun_RootSpecRun_ResultBuffer_UnsetLinkToResultBufferAfterRun() {
		$spec = new Spec();
		$spec->getExecutor()->setFunction(function() use(&$spec, &$resultBuffer) {
			$resultBuffer = $spec->getResultBuffer();
		});
		$spec->run();
		
		$this->assertNotSame($resultBuffer, $spec->getResultBuffer());
	}
	
	public function testRun_RootSpecRun_ResultBuffer_DoesNotClearResultBufferContentsAfterRun() {
		$counter = 0;
		$resultBuffers = array();
		
		config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$counter) {
			$counter++;
		
			$resultBuffer = $spec->getResultBuffer();
			$resultBuffer->addResult(false, "aaa" . $counter . "aaa");
			$resultBuffer->addResult(true, "bbb" . $counter . "bbb");
			$resultBuffer->addResult(null, "ccc" . $counter . "ccc");
		});
		
		config::registerEventListener('onSpecRunFinish', function(SpecInterface $spec) use(&$resultBuffers) {
			$resultBuffers[] = $spec->getResultBuffer();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		
		$this->assertSame(2, count($resultBuffers));
		
		$this->assertSame(array(
			array('result' => false, 'details' => 'aaa1aaa'),
			array('result' => true, 'details' => 'bbb1bbb'),
			array('result' => null, 'details' => 'ccc1ccc'),
		), $resultBuffers[0]->getResults());
		
		$this->assertSame(array(
			array('result' => false, 'details' => $specs[1]),
		), $resultBuffers[1]->getResults());
	}
	
	public function testRun_RootSpecRun_ResultBuffer_NotEndingSpec_PutsChildSpecRunResultWithChildSpecObjectToResultBufferForEachChildSpec() {
		$resultBuffers = array();
		config::registerEventListener('onSpecRunFinish', function(SpecInterface $spec) use(&$resultBuffers) {
			if ($spec === \spectrum\tests\_testware\tools::$temp["specs"][0]) {
				$resultBuffers[] = \spectrum\tests\_testware\tools::$temp["specs"][0]->getResultBuffer();
			}
		});

		$resultBufferClassName = \spectrum\tests\_testware\tools::createClass('
			class ... extends \spectrum\core\ResultBuffer {
				public function getTotalResult() {
					if ($this->getOwnerSpec() === \spectrum\tests\_testware\tools::$temp["specs"][1]) {
						return true;
					} else if ($this->getOwnerSpec() === \spectrum\tests\_testware\tools::$temp["specs"][2]) {
						return false;
					} else if ($this->getOwnerSpec() === \spectrum\tests\_testware\tools::$temp["specs"][3]) {
						return null;
					} else {
						return call_user_func_array("parent::getTotalResult", func_get_args());
					}
				}
			}
		');
		
		config::setClassReplacement('\spectrum\core\ResultBuffer', $resultBufferClassName);
		
		\spectrum\tests\_testware\tools::$temp["specs"] = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
		');
		
		\spectrum\tests\_testware\tools::$temp["specs"][0]->run();
		$this->assertSame(1, count($resultBuffers));
		$this->assertSame(array(
			array('result' => true, 'details' => \spectrum\tests\_testware\tools::$temp["specs"][1]),
			array('result' => false, 'details' => \spectrum\tests\_testware\tools::$temp["specs"][2]),
			array('result' => null, 'details' => \spectrum\tests\_testware\tools::$temp["specs"][3]),
		), $resultBuffers[0]->getResults());
	}

/**/
	
	public function testRun_RootSpecRun_Test_CallsFunctionOnEndingSpec() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');

		$callCount = 0;
		$specs[1]->getExecutor()->setFunction(function() use(&$callCount){ $callCount++; });
		$specs[0]->run();
		
		$this->assertSame(1, $callCount);
	}
	
	public function testRun_RootSpecRun_Test_DoesNotCallsFunctionOnNotEndingSpecs() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');

		$callCount = array('notEndingSpec' => 0, 'endingSpec' => 0);
		$specs[0]->getExecutor()->setFunction(function() use(&$callCount){ $callCount['notEndingSpec']++; });
		$specs[1]->getExecutor()->setFunction(function() use(&$callCount){ $callCount['endingSpec']++; });
		$specs[0]->run();
		
		$this->assertSame(array('notEndingSpec' => 0, 'endingSpec' => 1), $callCount);
	}
	
	public function testRun_RootSpecRun_Test_DoesNotPassArgumentsToFunction() {
		$spec = new Spec();
		$passedArguments = array();
		$spec->getExecutor()->setFunction(function() use(&$passedArguments){
			$passedArguments[] = func_get_args();
		});
		
		$spec->run();
		$this->assertSame(array(array()), $passedArguments);
	}
	
	public function testRun_RootSpecRun_Test_GetsFunctionFromAncestorOrSelf() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
			->->Spec
		', array(2 => 4));

		$calls = array();
		$specs[0]->getExecutor()->setFunction(function() use(&$calls){ $calls[] = 0; });
		$specs[1]->getExecutor()->setFunction(function() use(&$calls){ $calls[] = 1; });
		$specs[2]->getExecutor()->setFunction(function() use(&$calls){ $calls[] = 2; });
		$specs[0]->run();
		
		$this->assertSame(array(1, 2, 0), $calls);
	}
	
	public function testRun_RootSpecRun_Test_ApplyBeforeContextModifiersToDataBeforeFunctionCallAndInDirectOrder(){
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern('
			0
			|
			1
		');
		
		$appendValueToDataVariable = function($value) use(&$specs) {
			if (!isset($specs[1]->getData()->aaa)) {
				$specs[1]->getData()->aaa = '';
			}
			
			$specs[1]->getData()->aaa .= $value;
		};
		
		$specs[0]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('1'); }, 'before');
		$specs[0]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('2'); }, 'before');
		$specs[0]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('3'); }, 'after');
		$specs[0]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('4'); }, 'before');
		
		$specs[1]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('5'); }, 'before');
		$specs[1]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('6'); }, 'before');
		$specs[1]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('7'); }, 'after');
		$specs[1]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('8'); }, 'before');
		
		$properties = array();
		$specs[1]->getExecutor()->setFunction(function() use(&$properties, $specs) {
			$properties[] = get_object_vars($specs[1]->getData());
		});
		
		$specs[0]->run();
		
		$this->assertSame(array(array('aaa' => '124568')), $properties);
	}
	
	public function testRun_RootSpecRun_Test_ApplyAfterContextModifiersToDataAfterFunctionCallAndInBackwardOrder() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern('
			0
			|
			1
		');
		
		$appendValueToDataVariable = function($value) use(&$specs) {
			if (!isset($specs[1]->getData()->aaa)) {
				$specs[1]->getData()->aaa = '';
			}
			
			$specs[1]->getData()->aaa .= $value;
		};
		
		$specs[0]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('1'); }, 'after');
		$specs[0]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('2'); }, 'after');
		$specs[0]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('3'); }, 'before');
		$specs[0]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('4'); }, 'after');
		
		$specs[1]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('5'); }, 'after');
		$specs[1]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('6'); }, 'after');
		$specs[1]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('7'); }, 'before');
		$specs[1]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('8'); }, 'after');
		
		$properties = array();
		$dataItems = array();
		$specs[1]->getExecutor()->setFunction(function() use(&$properties, &$dataItems, $specs) {
			$properties[] = get_object_vars($specs[1]->getData());
			$dataItems[] = $specs[1]->getData();
		});
		
		$specs[0]->run();
		
		$this->assertSame(array(array('aaa' => '37')), $properties);
		$this->assertSame(array('aaa' => '37865421'), get_object_vars($dataItems[0]));
	}
	
	public function testRun_RootSpecRun_Test_FunctionIsNotSet_DoesNotTryToCallFunction() {
		$spec = new Spec();
		$spec->getExecutor()->setFunction(null);
		$spec->run();
	}
	
	public function testRun_RootSpecRun_Test_FunctionThrowsException_ApplyAfterContextModifiersToDataAfterFunctionCallAndInBackwardOrder() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern('
			0
			|
			1
		');
		
		$appendValueToDataVariable = function($value) use(&$specs) {
			if (!isset($specs[1]->getData()->aaa)) {
				$specs[1]->getData()->aaa = '';
			}
			
			$specs[1]->getData()->aaa .= $value;
		};
		
		$specs[0]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('1'); }, 'after');
		$specs[0]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('2'); }, 'after');
		$specs[0]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('3'); }, 'before');
		$specs[0]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('4'); }, 'after');
		
		$specs[1]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('5'); }, 'after');
		$specs[1]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('6'); }, 'after');
		$specs[1]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('7'); }, 'before');
		$specs[1]->getContextModifiers()->add(function() use($appendValueToDataVariable){ $appendValueToDataVariable('8'); }, 'after');
		
		$properties = array();
		$dataItems = array();
		$specs[1]->getExecutor()->setFunction(function() use(&$properties, &$dataItems, $specs){
			$properties[] = get_object_vars($specs[1]->getData());
			$dataItems[] = $specs[1]->getData();
			throw new \Exception();
		});
		
		$specs[0]->run();
		
		$this->assertSame(array(array('aaa' => '37')), $properties);
		$this->assertSame(array('aaa' => '37865421'), get_object_vars($dataItems[0]));
	}
	
/**/
	
	public function testRun_RootSpecRun_EventDispatch_DispatchesEventsInCorrectSequence() {
		$dispatchedEvents = array();
		
		config::registerEventListener('onRootSpecRunBefore', function() use(&$dispatchedEvents) { $dispatchedEvents[] = 'onRootSpecRunBefore'; });
		config::registerEventListener('onSpecRunBefore', function() use(&$dispatchedEvents) { $dispatchedEvents[] = 'onSpecRunBefore'; });
		config::registerEventListener('onSpecRunStart', function() use(&$dispatchedEvents) { $dispatchedEvents[] = 'onSpecRunStart'; });
		config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$dispatchedEvents) { $dispatchedEvents[] = 'onEndingSpecExecuteBefore'; });
		config::registerEventListener('onEndingSpecExecuteAfter', function() use(&$dispatchedEvents) { $dispatchedEvents[] = 'onEndingSpecExecuteAfter'; });
		config::registerEventListener('onSpecRunFinish', function() use(&$dispatchedEvents) { $dispatchedEvents[] = 'onSpecRunFinish'; });
		config::registerEventListener('onSpecRunAfter', function() use(&$dispatchedEvents) { $dispatchedEvents[] = 'onSpecRunAfter'; });
		config::registerEventListener('onRootSpecRunAfter', function() use(&$dispatchedEvents) { $dispatchedEvents[] = 'onRootSpecRunAfter'; });
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(
			'onRootSpecRunBefore',
			'onSpecRunBefore',
			'onSpecRunStart',
			
			'onEndingSpecExecuteBefore',
			'onEndingSpecExecuteAfter',
			
			'onSpecRunFinish',
			'onSpecRunAfter',
			'onRootSpecRunAfter',
		), $dispatchedEvents);
	}
	
/**/
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunBefore_CallsEventListenersInSpecifiedSequence() {
		$this->patternCallsEventListenersInSpecifiedSequence('onRootSpecRunBefore');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunBefore_CallsEventListenersWithSameOrderInRegistrationSequence() {
		$this->patternCallsEventListenersWithSameOrderInRegistrationSequence('onRootSpecRunBefore');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunBefore_PassesSpecToEventListeners() {
		$passedArguments = array();
		config::registerEventListener('onRootSpecRunBefore', function() use(&$passedArguments) {
			$passedArguments[] = func_get_args();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		
		$this->assertSame(array(array($specs[0])), $passedArguments);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunBefore_IsDispatchedOnRootSpecRunOnly() {
		$runSpecs = array();
		config::registerEventListener('onRootSpecRunBefore', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0]), $runSpecs);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunBefore_IsDispatchedWhenRunningFlagIsDisabled() {
		$isRunningCallResults = array();
		config::registerEventListener('onRootSpecRunBefore', function(SpecInterface $spec) use(&$isRunningCallResults) {
			$isRunningCallResults[] = $spec->isRunning();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(false), $isRunningCallResults);
	}

/**/
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunAfter_CallsEventListenersInSpecifiedSequence() {
		$this->patternCallsEventListenersInSpecifiedSequence('onRootSpecRunAfter');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunAfter_CallsEventListenersWithSameOrderInRegistrationSequence() {
		$this->patternCallsEventListenersWithSameOrderInRegistrationSequence('onRootSpecRunAfter');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunAfter_PassesSpecToEventListeners() {
		$passedArguments = array();
		config::registerEventListener('onRootSpecRunAfter', function() use(&$passedArguments) {
			$passedArguments[] = func_get_args();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array($specs[0])), $passedArguments);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunAfter_IsDispatchedOnRootSpecRunOnly() {
		$runSpecs = array();
		config::registerEventListener('onRootSpecRunAfter', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0]), $runSpecs);
	}

	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunAfter_IsDispatchedWhenRunningFlagIsDisabled() {
		$isRunningCallResults = array();
		config::registerEventListener('onRootSpecRunAfter', function(SpecInterface $spec) use(&$isRunningCallResults) {
			$isRunningCallResults[] = $spec->isRunning();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(false), $isRunningCallResults);
	}
	
/**/
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunBefore_CallsEventListenersInSpecifiedSequence() {
		$this->patternCallsEventListenersInSpecifiedSequence('onSpecRunBefore');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunBefore_CallsEventListenersWithSameOrderInRegistrationSequence() {
		$this->patternCallsEventListenersWithSameOrderInRegistrationSequence('onSpecRunBefore');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunBefore_PassesSpecToEventListeners() {
		$passedArguments = array();
		config::registerEventListener('onSpecRunBefore', function() use(&$passedArguments) {
			$passedArguments[] = func_get_args();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array($specs[0]), array($specs[1])), $passedArguments);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunBefore_IsDispatchedOnEverySpecRun() {
		$runSpecs = array();
		config::registerEventListener('onSpecRunBefore', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[1], $specs[2], $specs[3]), $runSpecs);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunBefore_IsDispatchedWhenRunningFlagIsDisabled() {
		$isRunningCallResults = array();
		config::registerEventListener('onSpecRunBefore', function(SpecInterface $spec) use(&$isRunningCallResults) {
			$isRunningCallResults[] = $spec->isRunning();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(false, false), $isRunningCallResults);
	}
	
/**/
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunAfter_CallsEventListenersInSpecifiedSequence() {
		$this->patternCallsEventListenersInSpecifiedSequence('onSpecRunAfter');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunAfter_CallsEventListenersWithSameOrderInRegistrationSequence() {
		$this->patternCallsEventListenersWithSameOrderInRegistrationSequence('onSpecRunAfter');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunAfter_PassesSpecToEventListeners() {
		$passedArguments = array();
		config::registerEventListener('onSpecRunAfter', function() use(&$passedArguments) {
			$passedArguments[] = func_get_args();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array($specs[1]), array($specs[0])), $passedArguments);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunAfter_IsDispatchedOnEverySpecRun() {
		$runSpecs = array();
		config::registerEventListener('onSpecRunAfter', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[2], $specs[1], $specs[3], $specs[0]), $runSpecs);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunAfter_IsDispatchedWhenRunningFlagIsDisabled() {
		$isRunningCallResults = array();
		config::registerEventListener('onSpecRunAfter', function(SpecInterface $spec) use(&$isRunningCallResults) {
			$isRunningCallResults[] = $spec->isRunning();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(false, false), $isRunningCallResults);
	}
	
/**/
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunStart_CallsEventListenersInSpecifiedSequence() {
		$this->patternCallsEventListenersInSpecifiedSequence('onSpecRunStart');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunStart_CallsEventListenersWithSameOrderInRegistrationSequence() {
		$this->patternCallsEventListenersWithSameOrderInRegistrationSequence('onSpecRunStart');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunStart_PassesSpecToEventListeners() {
		$passedArguments = array();
		config::registerEventListener('onSpecRunStart', function() use(&$passedArguments) {
			$passedArguments[] = func_get_args();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array($specs[0]), array($specs[1])), $passedArguments);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunStart_IsDispatchedOnEverySpecRun() {
		$runSpecs = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[1], $specs[2], $specs[3]), $runSpecs);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunStart_IsDispatchedWhenRunningFlagIsEnabled() {
		$isRunningCallResults = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$isRunningCallResults) {
			$isRunningCallResults[] = $spec->isRunning();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(true, true), $isRunningCallResults);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunStart_IsDispatchedBeforeChildSpecRun() {
		$runSpecs = array();
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[1], $specs[2]), $runSpecs);
	}
	
/**/
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunFinish_CallsEventListenersInSpecifiedSequence() {
		$this->patternCallsEventListenersInSpecifiedSequence('onSpecRunFinish');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunFinish_CallsEventListenersWithSameOrderInRegistrationSequence() {
		$this->patternCallsEventListenersWithSameOrderInRegistrationSequence('onSpecRunFinish');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunFinish_PassesSpecToEventListeners() {
		$passedArguments = array();
		config::registerEventListener('onSpecRunFinish', function() use(&$passedArguments) {
			$passedArguments[] = func_get_args();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array($specs[1]), array($specs[0])), $passedArguments);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunFinish_IsDispatchedOnEverySpecRun() {
		$runSpecs = array();
		config::registerEventListener('onSpecRunFinish', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[2], $specs[1], $specs[3], $specs[0]), $runSpecs);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunFinish_IsDispatchedWhenRunningFlagIsEnabled() {
		$isRunningCallResults = array();
		config::registerEventListener('onSpecRunFinish', function(SpecInterface $spec) use(&$isRunningCallResults) {
			$isRunningCallResults[] = $spec->isRunning();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(true, true), $isRunningCallResults);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunFinish_IsDispatchedAfterChildSpecsRun() {
		$runSpecs = array();
		config::registerEventListener('onSpecRunFinish', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[1], $specs[2], $specs[0]), $runSpecs);
	}
	
/**/
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteBefore_CallsEventListenersInSpecifiedSequence() {
		$this->patternCallsEventListenersInSpecifiedSequence('onEndingSpecExecuteBefore');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteBefore_CallsEventListenersWithSameOrderInRegistrationSequence() {
		$this->patternCallsEventListenersWithSameOrderInRegistrationSequence('onEndingSpecExecuteBefore');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteBefore_PassesSpecToEventListeners() {
		$passedArguments = array();
		config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$passedArguments) {
			$passedArguments[] = func_get_args();
		});
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(array($spec)), $passedArguments);
	}
		
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteBefore_IsDispatchedOnEndingSpecExecuteOnly() {
		$runSpecs = array();
		config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
			->->Spec
			->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[2], $specs[3], $specs[4], $specs[5]), $runSpecs);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteBefore_IsDispatchedWhenRunningFlagIsEnabled() {
		$isRunningCallResults = array();
		config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$isRunningCallResults) {
			$isRunningCallResults[] = $spec->isRunning();
		});
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(true), $isRunningCallResults);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteBefore_CatchesExceptionsAndAddsItToResultBufferAsFail() {
		$thrownExceptions = array();
		$resultBuffers = array();
		
		config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$thrownExceptions, &$resultBuffers) {
			$e = new \Exception("aaa");
			$thrownExceptions[] = $e;
			$resultBuffers[] = $spec->getResultBuffer();
			
			throw $e;
		});
		
				$spec = new Spec();
		$spec->run();
		
		$this->assertSame(1, count($thrownExceptions));
		
		$results = $resultBuffers[0]->getResults();
		$this->assertSame(array(
			array('result' => false, 'details' => $thrownExceptions[0]),
		), $results);
		
		$this->assertSame('aaa', $results[0]['details']->getMessage());
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteBefore_CatchesBreakExceptionAndDoesNotAddResultToResultBuffer() {
		$thrownExceptions = array();
		$resultBuffers = array();
		
		config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$thrownExceptions, &$resultBuffers) {
			$e = new \spectrum\core\BreakException();
			$thrownExceptions[] = $e;
			$resultBuffers[] = $spec->getResultBuffer();
			throw $e;
		});
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(1, count($thrownExceptions));
		$this->assertSame(array(), $resultBuffers[0]->getResults());
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteBefore_DoesNotBreakOtherEventListenersByException() {
		$calls = array();
		config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$calls) { $calls[] = 1; throw new \Exception(); }, 10);
		config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$calls) { $calls[] = 2; throw new \Exception(); }, 20);
		config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$calls) { $calls[] = 3; throw new \Exception(); }, 30);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(1, 2, 3), $calls);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteBefore_DoesNotBreakOtherEventListenersByBreakException() {
		$calls = array();
		config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$calls) { $calls[] = 1; throw new \spectrum\core\BreakException(); }, 10);
		config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$calls) { $calls[] = 2; throw new \spectrum\core\BreakException(); }, 20);
		config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$calls) { $calls[] = 3; throw new \spectrum\core\BreakException(); }, 30);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(1, 2, 3), $calls);
	}
	
/**/
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteAfter_CallsEventListenersInSpecifiedSequence() {
		$this->patternCallsEventListenersInSpecifiedSequence('onEndingSpecExecuteAfter');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteAfter_CallsEventListenersWithSameOrderInRegistrationSequence() {
		$this->patternCallsEventListenersWithSameOrderInRegistrationSequence('onEndingSpecExecuteAfter');
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteAfter_PassesSpecToEventListeners() {
		$passedArguments = array();
		config::registerEventListener('onEndingSpecExecuteAfter', function() use(&$passedArguments) {
			$passedArguments[] = func_get_args();
		});
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(array($spec)), $passedArguments);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteAfter_IsDispatchedOnEndingSpecExecuteOnly() {
		$runSpecs = array();
		config::registerEventListener('onEndingSpecExecuteAfter', function(SpecInterface $spec) use(&$runSpecs) {
			$runSpecs[] = $spec;
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->->Spec
			->->Spec
			->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[2], $specs[3], $specs[4], $specs[5]), $runSpecs);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteAfter_IsDispatchedWhenRunningFlagIsEnabled() {
		$isRunningCallResults = array();
		config::registerEventListener('onEndingSpecExecuteAfter', function(SpecInterface $spec) use(&$isRunningCallResults) {
			$isRunningCallResults[] = $spec->isRunning();
		});
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(true), $isRunningCallResults);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteAfter_CatchesExceptionsAndAddsItToResultBufferAsFail() {
		$thrownExceptions = array();
		$resultBuffers = array();
		
		config::registerEventListener('onEndingSpecExecuteAfter', function(SpecInterface $spec) use(&$thrownExceptions, &$resultBuffers) {
			$e = new \Exception("aaa");
			$thrownExceptions[] = $e;
			$resultBuffers[] = $spec->getResultBuffer();
			
			throw $e;
		});
		
				$spec = new Spec();
		$spec->run();
		
		$this->assertSame(1, count($thrownExceptions));
		
		$results = $resultBuffers[0]->getResults();
		$this->assertSame(array(
			array('result' => false, 'details' => $thrownExceptions[0]),
		), $results);
		
		$this->assertSame('aaa', $results[0]['details']->getMessage());
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteAfter_CatchesBreakExceptionAndDoesNotAddResultToResultBuffer() {
		$thrownExceptions = array();
		$resultBuffers = array();
		
		config::registerEventListener('onEndingSpecExecuteAfter', function(SpecInterface $spec) use(&$thrownExceptions, &$resultBuffers) {
			$e = new \spectrum\core\BreakException();
			$thrownExceptions[] = $e;
			$resultBuffers[] = $spec->getResultBuffer();
			throw $e;
		});
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(1, count($thrownExceptions));
		$this->assertSame(array(), $resultBuffers[0]->getResults());
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteAfter_DoesNotBreakOtherEventListenersByException() {
		$calls = array();
		config::registerEventListener('onEndingSpecExecuteAfter', function() use(&$calls) { $calls[] = 1; throw new \Exception(); }, 10);
		config::registerEventListener('onEndingSpecExecuteAfter', function() use(&$calls) { $calls[] = 2; throw new \Exception(); }, 20);
		config::registerEventListener('onEndingSpecExecuteAfter', function() use(&$calls) { $calls[] = 3; throw new \Exception(); }, 30);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(1, 2, 3), $calls);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnEndingSpecExecuteAfter_DoesNotBreakOtherEventListenersByBreakException() {
		$calls = array();
		config::registerEventListener('onEndingSpecExecuteAfter', function() use(&$calls) { $calls[] = 1; throw new \spectrum\core\BreakException(); }, 10);
		config::registerEventListener('onEndingSpecExecuteAfter', function() use(&$calls) { $calls[] = 2; throw new \spectrum\core\BreakException(); }, 20);
		config::registerEventListener('onEndingSpecExecuteAfter', function() use(&$calls) { $calls[] = 3; throw new \spectrum\core\BreakException(); }, 30);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(1, 2, 3), $calls);
	}
	
/**/
	
	public function testRun_RootSpecRun_Reports_OutputFormatIsHtml_AllowsOutputDataBuffering() {
		ob_start(function($buffer) use(&$html) {
			$html .= $buffer;
			return ''; 
		});
		
		config::setOutputFormat('html');
		$spec = new Spec();
		$spec->getExecutor()->setFunction(function() { throw new \Exception('<>&"\''); });
		$spec->run();
		
		ob_end_clean();
		
		$this->assertNotEquals('', $html);
		$this->assertContains('<html', $html);
		$this->assertContains('<body', $html);
		$this->assertContains('</body>', $html);
		$this->assertContains('</html>', $html);
	}
	
	public function testRun_RootSpecRun_Reports_OutputFormatIsHtml_GeneratesValidXhtml1StrictCode() {
		ob_start(function($buffer) use(&$html) {
			$html .= $buffer;
			return ''; 
		});
		
		config::setOutputFormat('html');
		config::setOutputIndention("\t");
		config::setOutputNewline("\r\n");
		
		$groupSpec = new Spec();
		
		// Tests for generating data by test
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getMatchers()->add('<>&"\'', function(){ throw new \Exception('<>&"\''); });
		$spec->getExecutor()->setFunction(function() use($spec) {
			$assert = new Assertion($spec, null);
			$assert->__call('<>&"\'');
		});
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getMatchers()->add('<>&"\'', function(){ return '<>&"\''; });
		$spec->getExecutor()->setFunction(function() use($spec) {
			$object = new \stdClass();
			$object->{'<>&"\''} = '<>&"\'';
			$object->aaa = array('<>&"\'' => '<>&"\'');
			
			$assert = new Assertion($spec, '<>&"\'');
			$assert->__call('<>&"\'', array(
				'<>&"\'',
				array('<>&"\'' => '<>&"\''),
				$object,
			));
		});
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getExecutor()->setFunction(function(){ throw new \Exception('<>&"\''); });
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getErrorHandling()->setCatchPhpErrors(true);
		$spec->getExecutor()->setFunction(function(){ trigger_error('<>&"\''); });
		
		// Tests for generating data by context modifiers with "before" type
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getExecutor()->setFunction(function(){});
		$spec->getMatchers()->add('<>&"\'', function(){ throw new \Exception('<>&"\''); });
		$spec->getContextModifiers()->add(function() use($spec) {
			$assert = new Assertion($spec, null);
			$assert->__call('<>&"\'');
		}, 'before');
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getExecutor()->setFunction(function(){});
		$spec->getMatchers()->add('<>&"\'', function(){ return '<>&"\''; });
		$spec->getContextModifiers()->add(function() use($spec) {
			$object = new \stdClass();
			$object->{'<>&"\''} = '<>&"\'';
			$object->aaa = array('<>&"\'' => '<>&"\'');
			
			$assert = new Assertion($spec, '<>&"\'');
			$assert->__call('<>&"\'', array(
				'<>&"\'',
				array('<>&"\'' => '<>&"\''),
				$object,
			));
		}, 'before');
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getExecutor()->setFunction(function(){});
		$spec->getContextModifiers()->add(function(){ throw new \Exception('<>&"\''); }, 'before');
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getExecutor()->setFunction(function(){});
		$spec->getErrorHandling()->setCatchPhpErrors(true);
		$spec->getContextModifiers()->add(function(){ trigger_error('<>&"\''); }, 'before');
		
		// Tests for generating data by context modifiers with "after" type
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getExecutor()->setFunction(function(){});
		$spec->getMatchers()->add('<>&"\'', function(){ throw new \Exception('<>&"\''); });
		$spec->getContextModifiers()->add(function() use($spec){
			$assert = new Assertion($spec, null);
			$assert->__call('<>&"\'');
		}, 'after');
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getExecutor()->setFunction(function(){});
		$spec->getMatchers()->add('<>&"\'', function(){ return '<>&"\''; });
		$spec->getContextModifiers()->add(function() use($spec){
			$object = new \stdClass();
			$object->{'<>&"\''} = '<>&"\'';
			$object->aaa = array('<>&"\'' => '<>&"\'');
			
			$assert = new Assertion($spec, '<>&"\'');
			$assert->__call('<>&"\'', array(
				'<>&"\'',
				array('<>&"\'' => '<>&"\''),
				$object,
			));
		}, 'after');
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getExecutor()->setFunction(function(){});
		$spec->getContextModifiers()->add(function(){ throw new \Exception('<>&"\''); }, 'after');
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getExecutor()->setFunction(function(){});
		$spec->getErrorHandling()->setCatchPhpErrors(true);
		$spec->getContextModifiers()->add(function(){ trigger_error('<>&"\''); }, 'after');
		
		// Tests for generating data by "\spectrum\core\details\*" classes
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->getExecutor()->setFunction(function() use($spec){
			$details = new \spectrum\core\details\MatcherCall();
			$details->setTestedValue('<>&"\'');
			$details->setNot('<>&"\'');
			$details->setResult('<>&"\'');
			$details->setMatcherName('<>&"\'');
			$details->setMatcherArguments(array('<>&"\'', '<>&"\'', '<>&"\''));
			$details->setMatcherReturnValue('<>&"\'');
			$details->setMatcherException('<>&"\'');
			$details->setFile('<>&"\'');
			$details->setLine('<>&"\'');
			$spec->getResultBuffer()->addResult(false, $details);
			
			$spec->getResultBuffer()->addResult(false, new \spectrum\core\details\PhpError('<>&"\'', '<>&"\'', '<>&"\'', '<>&"\''));
			$spec->getResultBuffer()->addResult(false, new \spectrum\core\details\UserFail('<>&"\''));
		});
		
		// Tests for "id" attribute uniqueness
		
		$spec1 = new Spec();
		$spec1->bindParentSpec($groupSpec);
		
		$spec2 = new Spec();
		$spec2->bindParentSpec($groupSpec);
		
		$spec3 = new Spec();
		$spec3->bindParentSpec($spec1);
		$spec3->bindParentSpec($spec2);
		$spec3->getExecutor()->setFunction(function(){});
		
		//
		
		$groupSpec->run();
		
		ob_end_clean();
		
		libxml_clear_errors();
		$domDocument = new \DOMDocument();
		
		$this->assertNotEquals('', $html);
		$this->assertTrue($domDocument->loadHTML($html));
		$this->assertTrue($domDocument->loadXML($html));
		$this->assertTrue($domDocument->schemaValidate(__DIR__ . '/../../_testware/xhtml1-strict.xsd'));
		$this->assertSame(array(), libxml_get_errors());
	}
	
	public function testRun_RootSpecRun_Reports_OutputFormatIsNotSupported_ThrowsException() {
		config::setOutputFormat('aaa');
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\Exception', 'Output format "aaa" is not supported', function() use($spec) {
			$spec->run();
		});
	}
	
/**/
	
	public function patternCallsEventListenersInSpecifiedSequence($testEventName) {
		$result = array();
		config::registerEventListener($testEventName, function() use(&$result) { $result[] = 20; }, 20);
		config::registerEventListener($testEventName, function() use(&$result) { $result[] = 30; }, 30);
		config::registerEventListener($testEventName, function() use(&$result) { $result[] = 10; }, 10);
		config::registerEventListener($testEventName, function() use(&$result) { $result[] = 50; }, 50);
		config::registerEventListener($testEventName, function() use(&$result) { $result[] = 40; }, 40);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(10, 20, 30, 40, 50), $result);
	}
	
	public function patternCallsEventListenersWithSameOrderInRegistrationSequence($testEventName) {
		$result = array();
		config::registerEventListener($testEventName, function() use(&$result) { $result[] = 1; }, 10);
		config::registerEventListener($testEventName, function() use(&$result) { $result[] = 2; }, 10);
		config::registerEventListener($testEventName, function() use(&$result) { $result[] = 3; }, 10);
		config::registerEventListener($testEventName, function() use(&$result) { $result[] = 4; }, 10);
		config::registerEventListener($testEventName, function() use(&$result) { $result[] = 5; }, 10);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(1, 2, 3, 4, 5), $result);
	}
}