<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins;

abstract class Plugin implements PluginInterface
{
	/** @var \spectrum\core\SpecInterface|\spectrum\core\Spec */
	private $ownerSpec;
	
	static public function getAccessName()
	{
		return null;
	}
	
	static public function getActivateMoment()
	{
		return 'firstAccess';
	}
	
	static public function getEventListeners()
	{
		return array();
	}

	public function __construct(\spectrum\core\SpecInterface $ownerSpec)
	{
		$this->ownerSpec = $ownerSpec;
	}

	/**
	 * @return \spectrum\core\SpecInterface|\spectrum\core\Spec
	 */
	public function getOwnerSpec()
	{
		return $this->ownerSpec;
	}
	
	protected  function callMethodThroughRunningAncestorSpecs($methodName, $args = array(), $returnByDefaultValue = null, $notSetCheckValue = null, $strict = true)
	{
		$ancestorSpecs = array_merge(array($this->getOwnerSpec()), $this->getOwnerSpec()->getRunningAncestorSpecs());
		
		foreach ($ancestorSpecs as $spec)
		{
			$plugin = $spec->{static::getAccessName()};
			
			$return = call_user_func_array(array($plugin, $methodName), $args);
			if (($strict && $return !== $notSetCheckValue) || (!$strict && $return != $notSetCheckValue))
				return $return;
		}

		return $returnByDefaultValue;
	}
	
	protected function dispatchPluginEvent($eventName, array $arguments = array())
	{
		$reflectionClass = new \ReflectionClass($this->getOwnerSpec());
		$reflectionMethod = $reflectionClass->getMethod('dispatchPluginEvent');
		$reflectionMethod->setAccessible(true);
		$reflectionMethod->invokeArgs($this->getOwnerSpec(), array($eventName, $arguments));
	}
	
	protected function handleModifyDeny()
	{
		if ($this->getOwnerSpec()->getRootSpec()->isRunning())
			throw new Exception('Modify spec plugins when spec tree is running deny');
	}
}