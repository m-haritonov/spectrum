<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins;

class Test extends \spectrum\core\plugins\Plugin
{
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
	
	protected function onEndingSpecExecute()
	{
		$function = $this->getFunctionThroughRunningAncestors();
		if ($function)
			$this->getOwnerSpec()->contexts->callFunctionInContext($function);
	}
}