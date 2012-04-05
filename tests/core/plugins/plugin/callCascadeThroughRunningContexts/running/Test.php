<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\running;
require_once dirname(__FILE__) . '/../../../../../init.php';

abstract class Test extends \spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\Test
{
	public function testHasParents_DescribeDescribeDescribe_AncestorsHasContexts_Level1ReturnIsNotEmpty_ShouldBeGetReturnFromCurrentRunningContext()
	{
		$specs = $this->createSpecsTree('
			Describe(notEmptySpec)
			->Context(context1_1)
			->Context(context1_2)
			->Context(context1_3)
			->Describe
			->->Context(context2_1)
			->->Context(context2_2)
			->->Context(context2_3)
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['notEmptySpec']->testPlugin->setFoo('notEmptySpec val');
		$specs['context1_1']->testPlugin->setFoo('context1_1 val');
		$specs['context1_2']->testPlugin->setFoo('context1_2 val');

		$specs['context2_1']->testPlugin->setFoo('context2_1 val');
		$specs['context2_2']->testPlugin->setFoo('context2_2 val');

		$this->executeContext(function() use($specs, &$results){
			$results[] = $specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array());
		}, $specs['spec']);

		$this->assertSame(array(
			'context2_1 val', // context2_1 <- context1_1
			'context2_2 val', // context2_2 <- context1_1
			'context1_1 val', // context2_3 <- context1_1

			'context2_1 val', // context2_1 <- context1_2
			'context2_2 val', // context2_2 <- context1_2
			'context1_2 val', // context2_3 <- context1_2

			'context2_1 val', // context2_1 <- context1_3
			'context2_2 val', // context2_2 <- context1_3
			'notEmptySpec val', // context2_3 <- context1_3
		), $results);
	}

	public function testHasParents_DescribeDescribeDescribe_AncestorsHasContexts_Level2ReturnIsNotEmpty_ShouldBeGetReturnFromCurrentRunningContext()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context(context1_1)
			->Context(context1_2)
			->Describe(notEmptySpec)
			->->Context(context2_1)
			->->Context(context2_2)
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['context1_1']->testPlugin->setFoo('context1_1 val');
		$specs['notEmptySpec']->testPlugin->setFoo('notEmptySpec val');
		$specs['context2_1']->testPlugin->setFoo('context2_1 val');

		$this->executeContext(function() use($specs, &$results){
			$results[] = $specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array());
		}, $specs['spec']);

		$this->assertSame(array(
			'context2_1 val', // context2_1 <- context1_1
			'notEmptySpec val', // context2_2 <- context1_1

			'context2_1 val', // context2_1 <- context1_2
			'notEmptySpec val', // context2_2 <- context1_2
		), $results);
	}

	public function testHasParents_DescribeDescribeDescribe_AncestorsHasDeepContexts_Level1ReturnIsNotEmpty_ShouldBeGetReturnFromCurrentDeepRunningContext()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context(context1)
			->->Context(context1_1)
			->Describe
			->->Context(context2)
			->->->Context(context2_1)
			->->->->Context(context2_1_1)
			->->->->Context(context2_1_2)
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['context1_1']->testPlugin->setFoo('context1_1 val');
		$specs['context2_1_1']->testPlugin->setFoo('context2_1_1 val');

		$this->executeContext(function() use($specs, &$results){
			$results[] = $specs['spec']->testPlugin->callCascadeThroughRunningContexts('getFoo', array());
		}, $specs['spec']);

		$this->assertSame(array(
			'context2_1_1 val', // context2_1_1 <- context1_1
			'context1_1 val', // context2_1_2 <- context1_1
		), $results);
	}
}