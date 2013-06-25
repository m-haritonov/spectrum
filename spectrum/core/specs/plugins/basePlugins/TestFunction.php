<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs\plugins\basePlugins;
use spectrum\config;
use spectrum\core\specs\ContextDataInterface;

class TestFunction extends \spectrum\core\specs\plugins\Plugin
{
	/**
	 * @var \Closure
	 */
	protected $function;
	protected $functionArguments = array();
	/** @var ContextDataInterface */
	protected $contextData;
	
	static public function getAccessName()
	{
		return 'testFunction';
	}
	
	static public function getEventListeners()
	{
		return array(
			array('event' => 'onEndingSpecExecute', 'method' => 'onEndingSpecExecute', 'order' => 10),
		);
	}
	
	public function setFunction($function)
	{
		$this->handleModifyDeny();
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

	public function setFunctionArguments(array $args)
	{
		$this->handleModifyDeny();
		$this->functionArguments = $args;
	}

	public function getFunctionArguments()
	{
		return $this->functionArguments;
	}
	
	public function getFunctionArgumentsThroughRunningAncestors()
	{
		return $this->callMethodThroughRunningAncestorSpecs('getFunctionArguments', array(), array(), null);
	}
	
/**/
	
	public function getContextData()
	{
		return $this->contextData;
	}
	
	protected function onEndingSpecExecute()
	{
		$function = $this->getFunctionThroughRunningAncestors();
		if (!$function)
			return;
		
		$contextDataClass = config::getContextDataClass();
		$this->contextData = new $contextDataClass();
		
		foreach ($this->getAllThroughRunningAncestors('before') as $context)
			$this->callFunctionInContext($context['function'], array());
		
		$this->dispatchPluginEvent('onTestFunctionCallBefore');
		$this->getOwnerSpec()->contexts->callFunctionInContext($function, $this->getFunctionArgumentsThroughRunningAncestors());
		$this->dispatchPluginEvent('onTestFunctionCallAfter');
		
		foreach ($this->getAllThroughRunningAncestors('after') as $context)
			$this->callFunctionInContext($context['function'], array());
	}
}