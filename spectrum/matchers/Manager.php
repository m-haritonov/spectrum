<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers;

class Manager implements ManagerInterface
{
	static public function addAllMatchersToSpec(\spectrum\core\SpecInterface $spec)
	{
		static::addBaseMatchersToSpec($spec);
	}
	
	static public function addBaseMatchersToSpec(\spectrum\core\SpecInterface $spec)
	{
		static::addMatcherToSpec($spec, '\spectrum\matchers\base\null');
		static::addMatcherToSpec($spec, '\spectrum\matchers\base\true');
		static::addMatcherToSpec($spec, '\spectrum\matchers\base\false');
		static::addMatcherToSpec($spec, '\spectrum\matchers\base\eq');
		static::addMatcherToSpec($spec, '\spectrum\matchers\base\ident');
		static::addMatcherToSpec($spec, '\spectrum\matchers\base\instanceofMatcher', 'instanceof');
		static::addMatcherToSpec($spec, '\spectrum\matchers\base\lt');
		static::addMatcherToSpec($spec, '\spectrum\matchers\base\lte');
		static::addMatcherToSpec($spec, '\spectrum\matchers\base\gt');
		static::addMatcherToSpec($spec, '\spectrum\matchers\base\gte');
		static::addMatcherToSpec($spec, '\spectrum\matchers\base\throwException');
	}
	
	static protected function addMatcherToSpec(\spectrum\core\SpecInterface $spec, $matcherCallbackName, $matcherName = null)
	{
		if (!$matcherName)
			$matcherName = str_replace('\spectrum\matchers\base\\', '', $matcherCallbackName);

		require_once __DIR__ . '/base/' . str_replace('\spectrum\matchers\base\\', '', $matcherCallbackName) . '.php';
		$spec->matchers->add($matcherName, $matcherCallbackName);
	}
}