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

namespace spectrum\core\plugins\basePlugins\stack\named\getCascadeThroughRunningContexts\running;
use spectrum\core\plugins\basePlugins\stack\Named;

require_once dirname(__FILE__) . '/../../../../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Test extends \spectrum\core\plugins\basePlugins\stack\named\getCascadeThroughRunningContexts\Test
{
	public function testHasParents_DescribeDescribeDescribe_AncestorsHasContexts_ItemExistsInLevel1_ShouldBeReturnValueFromCurrentRunningContext()
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

		$specs['notEmptySpec']->testPlugin->add('foo', 'notEmptySpec val');
		$specs['context1_1']->testPlugin->add('foo', 'context1_1 val');
		$specs['context1_2']->testPlugin->add('foo', 'context1_2 val');

		$specs['context2_1']->testPlugin->add('foo', 'context2_1 val');
		$specs['context2_2']->testPlugin->add('foo', 'context2_2 val');

		$this->executeContext(function() use($specs, &$results){
			$results[] = $specs['spec']->testPlugin->getCascadeThroughRunningContexts('foo');
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

	public function testHasParents_DescribeDescribeDescribe_AncestorsHasContexts_ItemExistsInLevel2_ShouldBeReturnValueFromCurrentRunningContext()
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

		$specs['context1_1']->testPlugin->add('foo', 'context1_1 val');
		$specs['notEmptySpec']->testPlugin->add('foo', 'notEmptySpec val');
		$specs['context2_1']->testPlugin->add('foo', 'context2_1 val');

		$this->executeContext(function() use($specs, &$results){
			$results[] = $specs['spec']->testPlugin->getCascadeThroughRunningContexts('foo');
		}, $specs['spec']);

		$this->assertSame(array(
			'context2_1 val', // context2_1 <- context1_1
			'notEmptySpec val', // context2_2 <- context1_1

			'context2_1 val', // context2_1 <- context1_2
			'notEmptySpec val', // context2_2 <- context1_2
		), $results);
	}

	public function testHasParents_DescribeDescribeDescribe_AncestorsHasDeepContexts_ItemExistsInLevel1_ShouldBeReturnValueFromCurrentDeepRunningContext()
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

		$specs['context1_1']->testPlugin->add('foo', 'context1_1 val');
		$specs['context2_1_1']->testPlugin->add('foo', 'context2_1_1 val');

		$this->executeContext(function() use($specs, &$results){
			$results[] = $specs['spec']->testPlugin->getCascadeThroughRunningContexts('foo');
		}, $specs['spec']);

		$this->assertSame(array(
			'context2_1_1 val', // context2_1_1 <- context1_1
			'context1_1 val', // context2_1_2 <- context1_1
		), $results);
	}
}