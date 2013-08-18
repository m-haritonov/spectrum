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

class Contexts extends \spectrum\core\plugins\Plugin
{
	protected $items = array();
	
	static public function getAccessName()
	{
		return 'contexts';
	}
	
	public function add($function, $type = 'before')
	{
		$this->handleModifyDeny();
		
		$type = strtolower($type);
		$this->checkType($type);
		
		$this->items[] = array(
			'function' => $function,
			'type' => $type,
		);
	}

	public function get($index)
	{
		return @$this->items[$index];
	}
	
	public function getThroughRunningAncestors($index)
	{
		$stack = $this->getOwnerSpec()->getRunningAncestorSpecs();
		$stack[] = $this->getOwnerSpec();
		$stack = array_reverse($stack);

		foreach ($stack as $spec)
		{
			if ($spec->{static::getAccessName()}->isExists($index))
				return $spec->{static::getAccessName()}->get($index);
		}

		return null;
	}
	
	public function getAll($type = null)
	{
		if ($type === null)
			return $this->items;
		else
		{
			$type = strtolower($type);
			$this->checkType($type);
			
			$resultItems = array();
			foreach ($this->items as $index => $item)
			{
				if ($item['type'] == $type)
					$resultItems[$index] = $item;
			}
			
			return $resultItems;
		}
	}
	
	/**
	 * Order "before": from parent to child
	 * Order "after": from child to parent
	 */
	public function getAllThroughRunningAncestors($joinOrder = 'before')
	{
		$joinOrder = strtolower($joinOrder);
		$this->checkType($joinOrder);
		
		$stack = $this->getOwnerSpec()->getRunningAncestorSpecs();
		$stack[] = $this->getOwnerSpec();

		$result = array();
		foreach ($stack as $spec)
		{
			if ($joinOrder == 'before')
				$result = array_merge($result, $spec->{static::getAccessName()}->getAll('before'));
			else
				$result = array_merge($spec->{static::getAccessName()}->getAll('after'), $result);
		}

		return $result;
	}
	
	public function isExists($index)
	{
		return array_key_exists($index, $this->items);
	}
	
	public function isExistsThroughRunningAncestors($index)
	{
		$stack = $this->getOwnerSpec()->getRunningAncestorSpecs();
		$stack[] = $this->getOwnerSpec();
		$stack = array_reverse($stack);

		foreach ($stack as $spec)
		{
			if ($spec->{static::getAccessName()}->isExists($index))
				return true;
		}

		return false;
	}
	
	public function remove($index)
	{
		$this->handleModifyDeny();
		
		$value = $this->items[$index];
		unset($this->items[$index]);
		return $value;
	}

	public function removeAll()
	{
		$this->handleModifyDeny();
		$this->items = array();
	}
	
	public function callFunctionInContext($function, $arguments, $context)
	{
		// Access to context through "$this" variable, available in php >= 5.4
		if (method_exists($function, 'bindTo'))
		{
			$function = $function->bindTo($context);
			if (!$function)
				throw new Exception('Can\'t bind "$this" variable to context object');
		}

		return call_user_func_array($function, $arguments);
	}
	
/**/
	
	protected function checkType($type)
	{
		$type = strtolower($type);
		if ($type != 'before' && $type != 'after')
			throw new Exception('Unknown type "' . $type . '" in plugin "' . static::getAccessName() . '"');
	}
}