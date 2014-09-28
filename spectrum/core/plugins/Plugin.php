<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins;

use spectrum\core\SpecInterface;
use spectrum\Exception;

abstract class Plugin implements PluginInterface {
	/**
	 * @var \spectrum\core\SpecInterface
	 */
	private $ownerSpec;
	
	static public function getAccessName() {
		return null;
	}
	
	static public function getActivateMoment() {
		return 'firstAccess';
	}
	
	static public function getEventListeners() {
		return array();
	}

	public function __construct(\spectrum\core\SpecInterface $ownerSpec) {
		$this->ownerSpec = $ownerSpec;
	}

	/**
	 * @return SpecInterface
	 */
	public function getOwnerSpec() {
		return $this->ownerSpec;
	}

	/**
	 * @param string $methodName
	 * @param mixed $defaultReturnValue
	 * @param mixed $ignoredReturnValue
	 * @param bool $useStrictComparison
	 * @return mixed|null
	 */
	protected function callMethodThroughRunningAncestorSpecs($methodName, array $arguments = array(), $defaultReturnValue = null, $ignoredReturnValue = null, $useStrictComparison = true) {
		$ancestorSpecs = array_merge(array($this->getOwnerSpec()), $this->getOwnerSpec()->getRunningAncestorSpecs());
		
		foreach ($ancestorSpecs as $spec) {
			$plugin = $spec->{static::getAccessName()};
			
			$return = call_user_func_array(array($plugin, $methodName), $arguments);
			if (($useStrictComparison && $return !== $ignoredReturnValue) || (!$useStrictComparison && $return != $ignoredReturnValue)) {
				return $return;
			}
		}

		return $defaultReturnValue;
	}

	/**
	 * @param string $eventName
	 */
	protected function dispatchPluginEvent($eventName, array $arguments = array()) {
		$reflectionClass = new \ReflectionClass($this->getOwnerSpec());
		$reflectionMethod = $reflectionClass->getMethod('dispatchPluginEvent');
		$reflectionMethod->setAccessible(true);
		$reflectionMethod->invokeArgs($this->getOwnerSpec(), array($eventName, $arguments));
	}

	/**
	 * @param string $functionName
	 */
	protected function handleModifyDeny($functionName) {
		foreach (array_merge(array($this->getOwnerSpec()), $this->getOwnerSpec()->getAncestorRootSpecs()) as $spec) {
			/** @var SpecInterface $spec */
			if ($spec->isRunning()) {
				throw new Exception('Call of "\\' . get_class($this) . '::' . $functionName . '" method is forbidden on run');
			}
		}
	}
}