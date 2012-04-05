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