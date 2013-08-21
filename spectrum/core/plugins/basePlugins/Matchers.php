<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;
use spectrum\config;
use spectrum\core\plugins\Exception;

class Matchers extends \spectrum\core\plugins\Plugin
{
	protected $items = array();
	static protected $denyOverrideMatchers = array();
	
	static public function getAccessName()
	{
		return 'matchers';
	}
	
	public function add($name, $function, $allowOverride = true)
	{
		$this->handleModifyDeny();
		
		if (in_array($name, static::$denyOverrideMatchers))
			throw new Exception('Matcher "' . $name . '" override deny');
		
		if (in_array($name, array('not', 'be')))
			throw new Exception('Name "' . $name . '" was reserved, you can\'t add matcher with same name');
		
		$this->items[$name] = $function;
		if (!$allowOverride)
			static::$denyOverrideMatchers[] = $name;
		return $function;
	}

	public function get($name)
	{
		return @$this->items[$name];
	}
	
	public function getThroughRunningAncestors($name)
	{
		$ancestorSpecs = array_merge(array($this->getOwnerSpec()), $this->getOwnerSpec()->getRunningAncestorSpecs());
		foreach ($ancestorSpecs as $spec)
		{
			if ($spec->{static::getAccessName()}->isExists($name))
				return $spec->{static::getAccessName()}->get($name);
		}

		return null;
	}
	
	public function getAll()
	{
		return $this->items;
	}
	

	public function isExists($name)
	{
		return array_key_exists($name, $this->items);
	}
	
	public function isExistsThroughRunningAncestors($name)
	{
		$ancestorSpecs = array_merge(array($this->getOwnerSpec()), $this->getOwnerSpec()->getRunningAncestorSpecs());
		foreach ($ancestorSpecs as $spec)
		{
			if ($spec->{static::getAccessName()}->isExists($name))
				return true;
		}

		return false;
	}

	public function remove($name)
	{
		$this->handleModifyDeny();
		
		if (in_array($name, static::$denyOverrideMatchers))
			throw new Exception('Matcher "' . $name . '" override deny');
		
		$value = $this->items[$name];
		unset($this->items[$name]);
		return $value;
	}
	
	public function removeAll()
	{
		$this->handleModifyDeny();
		
		foreach ($this->getAll() as $name => $matcher)
		{
			if (in_array($name, static::$denyOverrideMatchers))
				throw new Exception('Matcher "' . $name . '" override deny');
		}
		
		$this->items = array();
	}
}