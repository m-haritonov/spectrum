<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins;

class Matchers extends \spectrum\core\plugins\Plugin
{
	protected $items = array();
	
	static public function getAccessName()
	{
		return 'matchers';
	}
	
	public function add($name, $function)
	{
		$this->handleModifyDeny(__FUNCTION__);
		$this->items[$name] = $function;
	}

	public function get($name)
	{
		if (isset($this->items[$name]))
			return $this->items[$name];
		else
			return null;
	}
	
	public function getThroughRunningAncestors($name)
	{
		return $this->callMethodThroughRunningAncestorSpecs('get', array($name));
	}
	
	public function getAll()
	{
		return $this->items;
	}
	
	public function remove($name)
	{
		$this->handleModifyDeny(__FUNCTION__);
		unset($this->items[$name]);
	}
	
	public function removeAll()
	{
		$this->handleModifyDeny(__FUNCTION__);
		$this->items = array();
	}
}