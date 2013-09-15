<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\contexts;
use spectrum\config;
use spectrum\core\plugins\Exception;

class Contexts extends \spectrum\core\plugins\Plugin
{
	/** @var ContextDataInterface */
	protected $contextData;
	protected $items = array();
	
	static public function getAccessName()
	{
		return 'contexts';
	}
	
	static public function getEventListeners()
	{
		return array(
			array('event' => 'onEndingSpecExecuteBefore', 'method' => 'onEndingSpecExecuteBefore', 'order' => 20),
			array('event' => 'onEndingSpecExecuteAfter', 'method' => 'onEndingSpecExecuteAfter', 'order' => -20),
		);
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
		$ancestorSpecs = array_merge(array($this->getOwnerSpec()), $this->getOwnerSpec()->getRunningAncestorSpecs());
		foreach ($ancestorSpecs as $spec)
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
		
		$ancestorSpecs = array_merge(array($this->getOwnerSpec()), $this->getOwnerSpec()->getRunningAncestorSpecs());
		$ancestorSpecs = array_reverse($ancestorSpecs);
		
		$result = array();
		foreach ($ancestorSpecs as $spec)
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
		$ancestorSpecs = array_merge(array($this->getOwnerSpec()), $this->getOwnerSpec()->getRunningAncestorSpecs());

		foreach ($ancestorSpecs as $spec)
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
	
	public function getContextData()
	{
		return $this->contextData;
	}
	
	public function callFunctionInContext($function, array $arguments = array())
	{
		// Access to context through "$this" variable, available in php >= 5.4
		if (method_exists($function, 'bindTo'))
		{
			$function = $function->bindTo($this->contextData);
			if (!$function)
				throw new Exception('Can\'t bind "$this" variable to context object');
		}

		return call_user_func_array($function, $arguments);
	}
	
	protected function onEndingSpecExecuteBefore()
	{
		$this->contextData = $this->createContextData();
		
		foreach ($this->getAllThroughRunningAncestors('before') as $context)
			$this->callFunctionInContext($context['function']);
	}
	
	protected function onEndingSpecExecuteAfter()
	{
		foreach ($this->getAllThroughRunningAncestors('after') as $context)
			$this->callFunctionInContext($context['function']);
		
		$this->contextData = null;
	}
	
	protected function createContextData()
	{
		$contextClass = config::getContextDataClass();
		return new $contextClass();
	}
	
/**/
	
	protected function checkType($type)
	{
		$type = strtolower($type);
		if ($type != 'before' && $type != 'after')
			throw new Exception('Unknown type "' . $type . '" in plugin "' . static::getAccessName() . '"');
	}
}