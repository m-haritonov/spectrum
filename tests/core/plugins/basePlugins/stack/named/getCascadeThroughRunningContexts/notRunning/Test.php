<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\stack\named\getCascadeThroughRunningContexts\notRunning;
use spectrum\core\plugins\basePlugins\stack\Named;

require_once dirname(__FILE__) . '/../../../../../../../init.php';

abstract class Test extends \spectrum\core\plugins\basePlugins\stack\named\getCascadeThroughRunningContexts\Test
{
	public function testHasParents_DescribeDescribeDescribe_Level1HasSiblingWithItem_ShouldNotBeReturnValueFromSibling()
	{
		$specs = $this->createSpecsTree('
			Describe(sibling)
			Describe
			->Describe
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->add('foo', 'val1');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

	public function testHasParents_DescribeDescribeDescribe_Level2HasSiblingWithItem_ShouldNotBeReturnValueFromSibling()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe(sibling)
			->Describe
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->add('foo', 'val1');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

	public function testHasParents_DescribeDescribeDescribe_Level3HasSiblingWithItem_ShouldNotBeReturnValueFromSibling()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Describe(sibling)
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->add('foo', 'val1');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

	public function testHasParents_DescribeDescribeDescribe_SelfHasSiblingWithItem_ShouldNotBeReturnValueFromSibling()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Describe
			->->->' . $this->currentSpecClass . '(sibling)
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->add('foo', 'val1');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

/**/

	public function testHasParents_DescribeContextContext_Level1HasSiblingWithItem_ShouldNotBeReturnValueFromSibling()
	{
		$specs = $this->createSpecsTree('
			Describe(sibling)
			Describe
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->add('foo', 'val1');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

	public function testHasParents_DescribeContextContext_Level2HasSiblingWithItem_ShouldNotBeReturnValueFromSibling()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context(sibling)
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->add('foo', 'val1');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

	public function testHasParents_DescribeContextContext_Level3HasSiblingWithItem_ShouldNotBeReturnValueFromSibling()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context(sibling)
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->add('foo', 'val1');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

	public function testHasParents_DescribeContextContext_SelfHasSiblingWithItem_ShouldNotBeReturnValueFromSibling()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(sibling)
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->add('foo', 'val1');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

/**/

	public function testHasParents_ContextContextContext_Level1HasSiblingWithItem_ShouldNotBeReturnValueFromSibling()
	{
		$specs = $this->createSpecsTree('
			Context(sibling)
			Context
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->add('foo', 'val1');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

	public function testHasParents_ContextContextContext_Level2HasSiblingWithItem_ShouldNotBeReturnValueFromSibling()
	{
		$specs = $this->createSpecsTree('
			Context
			->Context(sibling)
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->add('foo', 'val1');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

	public function testHasParents_ContextContextContext_Level3HasSiblingWithItem_ShouldNotBeReturnValueFromSibling()
	{
		$specs = $this->createSpecsTree('
			Context
			->Context
			->->Context(sibling)
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->add('foo', 'val1');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}

	public function testHasParents_ContextContextContext_SelfHasSiblingWithItem_ShouldNotBeReturnValueFromSibling()
	{
		$specs = $this->createSpecsTree('
			Context
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(sibling)
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->add('foo', 'val1');

		$this->assertStackItemGettingThrowException('foo', $specs['spec']);
	}
}