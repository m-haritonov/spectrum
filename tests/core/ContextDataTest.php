<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\core;

use spectrum\core\ContextData;

require_once __DIR__ . '/../init.php';

class ContextDataTest extends \spectrum\tests\Test
{
	public function testSupportsAccessToPropertiesThroughObjectAccessStyle()
	{
		$context = new ContextData();
		$context->aaa = 'aaaVal';
		$context->bbb = 'bbbVal';
		$context->ccc = 'cccVal';

		$this->assertSame('aaaVal', $context->aaa);
		$this->assertSame('bbbVal', $context->bbb);
		$this->assertSame('cccVal', $context->ccc);
	}

	public function testSupportsAccessToPropertiesThroughArrayAccessStyle()
	{
		$context = new ContextData();
		$context['aaa-aaa'] = 'aaaVal';
		$context['bbb-bbb'] = 'bbbVal';
		$context['ccc-ccc'] = 'cccVal';

		$this->assertSame('aaaVal', $context['aaa-aaa']);
		$this->assertSame('bbbVal', $context['bbb-bbb']);
		$this->assertSame('cccVal', $context['ccc-ccc']);
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

		$this->assertSame(2, count($context));
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

		$this->assertSame(array(
			'aaa' => 'aaaVal',
			'bbb' => 'bbbVal',
			'ccc' => 'cccVal',
		), $values);
	}
}