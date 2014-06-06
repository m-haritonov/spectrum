<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins;

use spectrum\config;
use spectrum\core\DataInterface;

class Test extends \spectrum\core\plugins\Plugin
{
	/** @var DataInterface */
	protected $data;
	
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
	
	public function getData()
	{
		return $this->data;
	}
	
/**/
	
	protected function onEndingSpecExecute()
	{
		$function = $this->getFunctionThroughRunningAncestors();
		if ($function)
		{
			$this->data = $this->createData();
			
			foreach ($this->getOwnerSpec()->contextModifiers->getAllThroughRunningAncestors('before') as $context)
				$context['function']();
			
			$exception = null;
			try
			{
				$function();
			}
			catch (\Exception $e)
			{
				$exception = $e;
			}
			
			foreach ($this->getOwnerSpec()->contextModifiers->getAllThroughRunningAncestors('after') as $context)
				$context['function']();
			
			$this->data = null;
			
			if ($exception)
				throw $exception;
		}
	}
	
	protected function createData()
	{
		$contextClass = config::getClassReplacement('\spectrum\core\Data');
		return new $contextClass();
	}
}