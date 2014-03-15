<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins;

use spectrum\config;
use spectrum\core\ContextDataInterface;

class Test extends \spectrum\core\plugins\Plugin
{
	/** @var ContextDataInterface */
	protected $contextData;
	
	/**
	 * @var \Closure
	 */
	protected $function;
	
	static public function getAccessName()
	{
		return 'test';
	}
	
	static public function getEventListeners()
	{
		return array(
			array('event' => 'onEndingSpecExecute', 'method' => 'onEndingSpecExecute', 'order' => 10),
		);
	}
	
	public function setFunction($function)
	{
		$this->handleModifyDeny(__FUNCTION__);
		$this->function = $function;
	}

	public function getFunction()
	{
		return $this->function;
	}
	
	public function getFunctionThroughRunningAncestors()
	{
		return $this->callMethodThroughRunningAncestorSpecs('getFunction', array(), null, null);
	}

/**/
	
	public function getContextData()
	{
		return $this->contextData;
	}
	
/**/
	
	protected function onEndingSpecExecute()
	{
		$function = $this->getFunctionThroughRunningAncestors();
		if ($function)
		{
			$this->contextData = $this->createContextData();
			
			$callFunctionOnContextDataFunction = config::getFunctionReplacement('\spectrum\_internal\callFunctionOnContextData');
			foreach ($this->getOwnerSpec()->contextModifiers->getAllThroughRunningAncestors('before') as $context)
				$callFunctionOnContextDataFunction($context['function'], array(), $this->contextData);
			
			$callFunctionOnContextDataFunction = config::getFunctionReplacement('\spectrum\_internal\callFunctionOnContextData');
			$callFunctionOnContextDataFunction($function, array(), $this->contextData);
			
			$callFunctionOnContextDataFunction = config::getFunctionReplacement('\spectrum\_internal\callFunctionOnContextData');
			foreach ($this->getOwnerSpec()->contextModifiers->getAllThroughRunningAncestors('after') as $context)
				$callFunctionOnContextDataFunction($context['function'], array(), $this->contextData);
			
			$this->contextData = null;
		}
	}
	
	protected function createContextData()
	{
		$contextClass = config::getClassReplacement('\spectrum\core\ContextData');
		return new $contextClass();
	}
}