<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins;

use spectrum\config;
use spectrum\core\DataInterface;

class Test extends \spectrum\core\plugins\Plugin {
	/**
	 * @var null|DataInterface
	 */
	protected $data;
	
	/**
	 * @var null|\Closure
	 */
	protected $function;

	/**
	 * @return string
	 */
	static public function getAccessName() {
		return 'test';
	}

	/**
	 * @return array
	 */
	static public function getEventListeners() {
		return array(
			array('event' => 'onEndingSpecExecute', 'method' => 'onEndingSpecExecute', 'order' => 10),
		);
	}

	/**
	 * @param \Closure $function
	 */
	public function setFunction($function) {
		$this->handleModifyDeny(__FUNCTION__);
		$this->function = $function;
	}

	/**
	 * @return null|\Closure
	 */
	public function getFunction() {
		return $this->function;
	}

	/**
	 * @return null|\Closure
	 */
	public function getFunctionThroughRunningAncestors() {
		return $this->callMethodThroughRunningAncestorSpecs('getFunction', array(), null, null);
	}

/**/

	/**
	 * @return null|DataInterface
	 */
	public function getData() {
		return $this->data;
	}
	
/**/
	
	protected function onEndingSpecExecute() {
		$function = $this->getFunctionThroughRunningAncestors();
		if ($function) {
			$this->data = $this->createData();
			
			foreach ($this->getOwnerSpec()->contextModifiers->getAllThroughRunningAncestors('before') as $context) {
				$context['function']();
			}
			
			$exception = null;
			try {
				$function();
			} catch (\Exception $e) {
				$exception = $e;
			}
			
			foreach ($this->getOwnerSpec()->contextModifiers->getAllThroughRunningAncestors('after') as $context) {
				$context['function']();
			}
			
			$this->data = null;
			
			if ($exception) {
				throw $exception;
			}
		}
	}

	/**
	 * @return DataInterface
	 */
	protected function createData() {
		$dataClass = config::getClassReplacement('\spectrum\core\Data');
		return new $dataClass();
	}
}