<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace spectrum\core\plugins\basePlugins\stack\named\getCascadeThroughRunningContexts;
use spectrum\core\plugins\basePlugins\stack\Named;

require_once dirname(__FILE__) . '/../../../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Test extends \spectrum\core\plugins\basePlugins\stack\named\Test
{
	public function testNoParents_ItemNotExists_ShouldBeThrowException()
	{
		$specs = $this->createSpecsTree($this->currentSpecClass . '(spec)');
		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

	public function testNoParents_ItemExists_ShouldBeReturnValueFromSelf()
	{
		$specs = $this->createSpecsTree($this->currentSpecClass . '(spec)');
		$specs['spec']->testPlugin->add('foo', 'val1');
		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_Describe_ItemNotExists_ShouldBeThrowException()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(spec)
		');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

	public function testHasParents_Describe_ItemExistsInParent_ShouldBeReturnValueFromParent()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

	public function testHasParents_Describe_ItemExistsInParentAndEqualNull_ShouldBeReturnValueFromParent()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', null);

		$this->assertStackItemValueSame(null, array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_Context_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Context
			->' . $this->currentSpecClass . '(spec)
		');
		
		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

	public function testHasParents_Context_ItemExistsOnlyInSelf_ShouldBeReturnValueFromSelf()
	{
		$specs = $this->createSpecsTree('
			Context
			->' . $this->currentSpecClass . '(spec)
		');

		$specs['spec']->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

	public function testHasParents_Context_ItemExistsInAll_ShouldBeReturnValueFromSelf()
	{
		$specs = $this->createSpecsTree('
			Context
			->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');
		$specs['spec']->testPlugin->add('foo', 'val2');

		$this->assertStackItemValueSame('val2', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_DescribeDescribe_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

	public function testHasParents_DescribeDescribe_ItemExistsOnlyInLevel2_ShouldBeReturnValueFromLevel2()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[1]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

	public function testHasParents_DescribeDescribe_ItemExistsInLevel1AndLevel2_ShouldBeReturnValueFromLevel2()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');
		$specs[1]->testPlugin->add('foo', 'val2');

		$this->assertStackItemValueSame('val2', array($specs['spec'], 'foo'));
	}

	public function testHasParents_DescribeDescribe_ItemExistsInAll_ShouldBeReturnValueFromSelf()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');
		$specs[1]->testPlugin->add('foo', 'val2');
		$specs['spec']->testPlugin->add('foo', 'val3');

		$this->assertStackItemValueSame('val3', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_ContextContext_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Context
			->Context
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_DescribeContext_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_ContextDescribe_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Context
			->Describe
			->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_DescribeDescribeDescribe_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_DescribeContextDescribe_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_DescribeDescribeContext_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_DescribeContextContext_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_ContextContextContext_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Context
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_ContextDescribeContext_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Context
			->Describe
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_ContextContextDescribe_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Context
			->Context
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

/**/

	public function testHasParents_ContextDescribeDescribe_ItemExistsOnlyInLevel1_ShouldBeReturnValueFromLevel1()
	{
		$specs = $this->createSpecsTree('
			Context
			->Describe
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs[0]->testPlugin->add('foo', 'val1');

		$this->assertStackItemValueSame('val1', array($specs['spec'], 'foo'));
	}

/*** Test ware ***/

	abstract protected function executeContext($callback, \spectrum\core\SpecInterface $spec);

	protected function assertStackItemGettingThrowException($itemKey, \spectrum\core\SpecInterface $spec)
	{
		$self = $this;
		$this->executeContext(function() use($spec, $itemKey, $self)
		{
			$self->assertThrowException('\spectrum\core\plugins\Exception', '"' . $itemKey . '" not exists', function() use($spec, $itemKey){
				$spec->testPlugin->getCascadeThroughRunningContexts($itemKey);
			});
		}, $spec);
	}

	protected function assertStackItemValueSame($expectedValue, array $specAndItemKey)
	{
		$spec = $specAndItemKey[0];
		$key = $specAndItemKey[1];

		if (!($spec instanceof \spectrum\core\SpecInterface))
			throw new \Exception('Wrong spec passed');

		$this->executeContext(function() use($spec, $key, &$result){
			$result = $spec->testPlugin->getCascadeThroughRunningContexts($key);
		}, $spec);

		$this->assertSame($expectedValue, $result);
	}
}