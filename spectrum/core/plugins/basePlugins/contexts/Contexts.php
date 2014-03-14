<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\contexts;
use spectrum\config;
use spectrum\core\plugins\Exception;

class Contexts extends \spectrum\core\plugins\Plugin
{
	/** @var DataInterface */
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
		$this->handleModifyDeny(__FUNCTION__);
		
		$convertLatinCharsToLowerCaseFunction = config::getFunctionReplacement('\spectrum\_internal\convertLatinCharsToLowerCase');
		$type = $convertLatinCharsToLowerCaseFunction($type);
		
		$this->checkType($type);
		
		$this->items[] = array(
			'function' => $function,
			'type' => $type,
		);
	}
	
	public function getAll($type = null)
	{
		if ($type === null)
			return $this->items;
		else
		{
			$convertLatinCharsToLowerCaseFunction = config::getFunctionReplacement('\spectrum\_internal\convertLatinCharsToLowerCase');
			$type = $convertLatinCharsToLowerCaseFunction($type);
			
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
	 * "before" type: order is from parent to child
	 * "after" type: order is from child to parent
	 */
	public function getAllThroughRunningAncestors($type = 'before')
	{
		$convertLatinCharsToLowerCaseFunction = config::getFunctionReplacement('\spectrum\_internal\convertLatinCharsToLowerCase');
		$type = $convertLatinCharsToLowerCaseFunction($type);
		
		$this->checkType($type);
		
		$ancestorSpecs = array_merge(array($this->getOwnerSpec()), $this->getOwnerSpec()->getRunningAncestorSpecs());
		
		$result = array();
		foreach ($ancestorSpecs as $spec)
		{
			if ($type == 'before')
				$result = array_merge($spec->{static::getAccessName()}->getAll('before'), $result);
			else
				$result = array_merge($result, array_reverse($spec->{static::getAccessName()}->getAll('after')));
		}

		return $result;
	}
	
	public function remove($index)
	{
		$this->handleModifyDeny(__FUNCTION__);
		unset($this->items[$index]);
	}

	public function removeAll()
	{
		$this->handleModifyDeny(__FUNCTION__);
		$this->items = array();
	}
	
	public function getContextData()
	{
		return $this->contextData;
	}
	
	public function callFunctionInContext($function, array $arguments = array())
	{
		if (!$this->contextData)
			throw new Exception('Context data is not initialized (call this method on spec run)');
		
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
		if ($type != 'before' && $type != 'after')
			throw new Exception('Unknown type "' . $type . '" in plugin "' . static::getAccessName() . '"');
	}
}