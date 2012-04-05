<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;
use spectrum\core\Config;
use spectrum\core\plugins\Exception;

class Matchers extends Stack\Named
{
	public function add($name, $callback)
	{
		if (!Config::getAllowMatchersAdd())
			throw new Exception('Matchers add deny in Config');

		if (!Config::getAllowMatchersOverride() && $this->isExists($name))
			throw new Exception('Matchers override deny in Config');

		$reflection = new \ReflectionClass(Config::getAssertClass());
		if ($reflection->hasMethod($name) && $reflection->getMethod($name)->isPublic())
			throw new Exception('Can\'t add matcher with name "' . $name . '": public method with same name already exists in class "' . Config::getAssertClass() . '"');

		if (in_array($name, array('not', 'be')))
			throw new Exception('Name "' . $name . '" was reserved, you can\'t add matcher with same name');

		return parent::add($name, $callback);
	}

	public function remove($name)
	{
		if (!Config::getAllowMatchersOverride())
			throw new Exception('Matchers override deny in Config');

		return parent::remove($name);
	}

	public function removeAll()
	{
		if (!Config::getAllowMatchersOverride())
			throw new Exception('Matchers override deny in Config');

		return parent::removeAll();
	}

	public function callMatcher($name, array $args = array())
	{
		$callback = $this->getCascadeThroughRunningContexts($name);

		if (!is_callable($callback))
			throw new Exception('Callback for matcher "' . $name . '" is not callable');

		return call_user_func_array($callback, $args);
	}

	protected function doNotExistsInCascade($key)
	{
		throw new Exception('Matcher "' . $key . '" not exists');
	}
}