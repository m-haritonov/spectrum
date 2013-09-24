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
	
	static public function getAccessName()
	{
		return 'matchers';
	}
	
	public function add($name, $function)
	{
		$this->handleModifyDeny();
		$this->items[$name] = $function;
	}

	public function get($name)
	{
		return @$this->items[$name];
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
		$this->handleModifyDeny();
		unset($this->items[$name]);
	}
	
	public function removeAll()
	{
		$this->handleModifyDeny();
		$this->items = array();
	}
}