<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;

class TestFunction extends \spectrum\core\plugins\Plugin
{
	/**
	 * @var \Closure
	 */
	protected $function;
	
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
	
	protected function onEndingSpecExecute()
	{
		$function = $this->getFunctionThroughRunningAncestors();
		if ($function)
			$this->getOwnerSpec()->contexts->callFunctionInContext($function);
	}
}