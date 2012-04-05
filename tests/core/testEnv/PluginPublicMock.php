<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv;

class PluginPublicMock extends \spectrum\core\plugins\Plugin
{
	private $foo;

	public function callCascadeThroughRunningContexts()
	{
//		if ($this->ownerSpec->getParent())
//		{
//			if ($this->ownerSpec->getParent()->testPlugin->getFoo() === true)
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
		\spectrum\Test::$tmp['getFoo']['arguments'] = func_get_args();
		return $this->foo;
	}
}