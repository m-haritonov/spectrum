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

namespace net\mkharitonov\spectrum\matchers;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Manager implements ManagerInterface
{
	static public function addAllMatchersToSpec(\net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		static::addBaseMatchersToSpec($spec);
	}
	
	static public function addBaseMatchersToSpec(\net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		static::addMatcherToSpec('\net\mkharitonov\spectrum\matchers\base\null', $spec);
		static::addMatcherToSpec('\net\mkharitonov\spectrum\matchers\base\true', $spec);
		static::addMatcherToSpec('\net\mkharitonov\spectrum\matchers\base\false', $spec);
		static::addMatcherToSpec('\net\mkharitonov\spectrum\matchers\base\eq', $spec);
		static::addMatcherToSpec('\net\mkharitonov\spectrum\matchers\base\ident', $spec);
		static::addMatcherToSpec('\net\mkharitonov\spectrum\matchers\base\lt', $spec);
		static::addMatcherToSpec('\net\mkharitonov\spectrum\matchers\base\lte', $spec);
		static::addMatcherToSpec('\net\mkharitonov\spectrum\matchers\base\gt', $spec);
		static::addMatcherToSpec('\net\mkharitonov\spectrum\matchers\base\gte', $spec);
		static::addMatcherToSpec('\net\mkharitonov\spectrum\matchers\base\throwException', $spec);
	}
	
	static protected function addMatcherToSpec($matcherCallbackName, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		$matcherName = str_replace('\net\mkharitonov\spectrum\matchers\base\\', '', $matcherCallbackName);

		require_once __DIR__ . '/base/' . $matcherName . '.php';
		$spec->matchers->add($matcherName, $matcherCallbackName);
	}
}