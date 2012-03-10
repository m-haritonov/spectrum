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

namespace net\mkharitonov\spectrum\core\testEnv;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class PluginPublicMock extends \net\mkharitonov\spectrum\core\plugins\Plugin
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
		\net\mkharitonov\spectrum\Test::$tmp['getFoo']['arguments'] = func_get_args();
		return $this->foo;
	}
}