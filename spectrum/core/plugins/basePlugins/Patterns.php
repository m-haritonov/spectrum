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

namespace spectrum\core\plugins\basePlugins;
use spectrum\core\Config;
use spectrum\core\plugins\Exception;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Patterns extends Stack\Named
{
	public function add($name, $callback)
	{
//		if (!Config::getAllowMatchersAdd())
//			throw new Exception('Matchers add deny in Config');
//
//		if (!Config::getAllowMatchersOverride() && $this->isExists($name))
//			throw new Exception('Matchers override deny in Config');

		return parent::add($name, $callback);
	}

	public function remove($name)
	{
//		if (!Config::getAllowMatchersOverride())
//			throw new Exception('Matchers override deny in Config');

		return parent::remove($name);
	}

	public function removeAll()
	{
//		if (!Config::getAllowMatchersOverride())
//			throw new Exception('Matchers override deny in Config');

		return parent::removeAll();
	}

//	public function callMatcher($name, array $args = array())
//	{
//		$callback = $this->getCascadeThroughRunningContexts($name);
//
//		if (!is_callable($callback))
//			throw new \spectrum\core\Exception('Callback for pattern "' . $name . '" is not callable');
//
//		return call_user_func_array($callback, $args);
//	}
}