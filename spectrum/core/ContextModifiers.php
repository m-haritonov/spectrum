<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

class ContextModifiers implements ContextModifiersInterface {
	/**
	 * @var array
	 */
	protected $items = array();

	/**
	 * @var SpecInterface
	 */
	protected $ownerSpec;
	
	public function __construct(SpecInterface $ownerSpec) {
		$this->ownerSpec = $ownerSpec;
	}
	
	/**
	 * @param callable $function
	 * @param string $type "before" or "after"
	 */
	public function add($function, $type = 'before') {
		$handleSpecModifyDenyFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this->ownerSpec, $this, __FUNCTION__);
		
		$convertLatinCharsToLowerCaseFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\convertLatinCharsToLowerCase');
		$type = $convertLatinCharsToLowerCaseFunction($type);
		
		$this->checkType($type, __FUNCTION__);
		
		$this->items[] = array(
			'function' => $function,
			'type' => $type,
		);
	}

	/**
	 * @param null|string $type null or "before" of "after"
	 * @return array
	 */
	public function getAll($type = null) {
		if ($type === null) {
			return $this->items;
		} else {
			$convertLatinCharsToLowerCaseFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\convertLatinCharsToLowerCase');
			$type = $convertLatinCharsToLowerCaseFunction($type);
			
			$this->checkType($type, __FUNCTION__);
			
			$resultItems = array();
			foreach ($this->items as $index => $item) {
				if ($item['type'] === $type) {
					$resultItems[$index] = $item;
				}
			}
			
			return $resultItems;
		}
	}
	
	/**
	 * @param string $type When type if "before": order is from parent to child; when type is "after": order is from child to parent
	 * @return array
	 */
	public function getAllThroughRunningAncestors($type = 'before') {
		$convertLatinCharsToLowerCaseFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\convertLatinCharsToLowerCase');
		$type = $convertLatinCharsToLowerCaseFunction($type);
		
		$this->checkType($type, __FUNCTION__);
		
		$ancestorSpecs = array_merge(array($this->ownerSpec), $this->ownerSpec->getRunningAncestorSpecs());
		
		$result = array();
		foreach ($ancestorSpecs as $spec) {
			/** @var SpecInterface $spec */
			if ($type === 'before') {
				$result = array_merge($spec->getContextModifiers()->getAll('before'), $result);
			} else if ($type === 'after') {
				$result = array_merge($result, array_reverse($spec->getContextModifiers()->getAll('after')));
			}
		}

		return $result;
	}

	/**
	 * @param int $index
	 */
	public function remove($index) {
		$handleSpecModifyDenyFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this->ownerSpec, $this, __FUNCTION__);
		
		unset($this->items[$index]);
	}

	public function removeAll() {
		$handleSpecModifyDenyFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this->ownerSpec, $this, __FUNCTION__);
		
		$this->items = array();
	}
	
/**/

	/**
	 * @param string $type
	 * @param string $callerFunctionName
	 */
	protected function checkType($type, $callerFunctionName) {
		if ($type !== 'before' && $type !== 'after') {
			throw new Exception('Unknown type "' . $type . '" is passed to "\\' . get_class($this) . '::' . $callerFunctionName . '" method');
		}
	}
}