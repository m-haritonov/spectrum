<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins\basePlugins\contexts;
use spectrum\core\plugins\basePlugins\contexts\ContextData;

require_once __DIR__ . '/../../../../init.php';

class ContextDataTest extends \spectrum\tests\Test
{
	public function testSupportsAccessToPropertiesThroughObjectAccessStyle()
	{
		$context = new ContextData();
		$context->aaa = 'aaaVal';
		$context->bbb = 'bbbVal';
		$context->ccc = 'cccVal';

		$this->assertEquals('aaaVal', $context->aaa);
		$this->assertEquals('bbbVal', $context->bbb);
		$this->assertEquals('cccVal', $context->ccc);
	}

	public function testSupportsAccessToPropertiesThroughArrayAccessStyle()
	{
		$context = new ContextData();
		$context['aaa-aaa'] = 'aaaVal';
		$context['bbb-bbb'] = 'bbbVal';
		$context['ccc-ccc'] = 'cccVal';

		$this->assertEquals('aaaVal', $context['aaa-aaa']);
		$this->assertEquals('bbbVal', $context['bbb-bbb']);
		$this->assertEquals('cccVal', $context['ccc-ccc']);
	}

	public function testSupportsPropertyExistCheck()
	{
		$context = new ContextData();
		$this->assertFalse(isset($context->aaa));
		$context->aaa = 'aaaVal';
		$this->assertTrue(isset($context->aaa));
	}

 	public function testSupportsPropertyUnset()
	{
		$context = new ContextData();
		$context->aaa = 'aaaVal';
		unset($context->aaa);
		$this->assertFalse(property_exists($context, 'aaa'));
	}

 	public function testSupportsPropertyCountCheck()
	{
		$context = new ContextData();
		$context->aaa = 'aaaVal';
		$context->bbb = 'bbbVal';

		$this->assertEquals(2, count($context));
	}

	public function testSupportsForeachTraverse()
	{
		$context = new ContextData();
		$context->aaa = 'aaaVal';
		$context->bbb = 'bbbVal';
		$context->ccc = 'cccVal';

		$values = array();
		foreach ($context as $key => $val)
			$values[$key] = $val;

		$this->assertEquals(array(
			'aaa' => 'aaaVal',
			'bbb' => 'bbbVal',
			'ccc' => 'cccVal',
		), $values);
	}
}