<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins;

use spectrum\config;
use spectrum\Exception;

class ContextModifiers extends \spectrum\core\plugins\Plugin
{
	protected $items = array();
	
	static public function getAccessName()
	{
		return 'contextModifiers';
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
				if ((string) $item['type'] === (string) $type)
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
			if ((string) $type === 'before')
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
	
/**/
	
	protected function checkType($type)
	{
		if ($type != 'before' && $type != 'after')
			throw new Exception('Unknown type "' . $type . '" in plugin "' . static::getAccessName() . '"');
	}
}