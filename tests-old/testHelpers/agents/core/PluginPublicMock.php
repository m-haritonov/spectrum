<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\testHelpers\agents\core;

class PluginPublicMock extends \spectrum\core\plugins\Plugin
{
	private $foo;

	public function callCascade()
	{
//		if ($this->ownerSpec->getParentSpec())
//		{
//			if ($this->ownerSpec->getParentSpec()->testPlugin->getFoo() === true)
//				return false;
//		}

		return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
	}

	public function setFoo($value)
	{
		$this->foo = $value;
	}

	public function getFoo()
	{
		\spectrum\tests\Test::$temp['getFoo']['arguments'] = func_get_args();
		return $this->foo;
	}
}