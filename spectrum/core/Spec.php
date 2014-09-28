<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

use spectrum\config;
use spectrum\core\plugins\PluginInterface;
use spectrum\Exception;

/**
 * @property \spectrum\core\plugins\ContextModifiers contextModifiers
 * @property \spectrum\core\plugins\ErrorHandling errorHandling
 * @property \spectrum\core\plugins\reports\Reports reports
 * @property \spectrum\core\plugins\Matchers matchers
 * @property \spectrum\core\plugins\Messages messages
 * @property \spectrum\core\plugins\Test test
 */
class Spec implements SpecInterface {
	/**
	 * @var array
	 */
	protected $activatedPlugins = array();

	/**
	 * @var bool
	 */
	protected $isEnabled = true;

	/**
	 * @var null|string|int|float
	 */
	protected $name;
	
	/**
	 * @var SpecInterface[]
	 */
	protected $parentSpecs = array();
	
	/**
	 * @var SpecInterface[]
	 */
	protected $childSpecs = array();

	/**
	 * @var null|ResultBufferInterface
	 */
	protected $resultBuffer;

	/**
	 * @var bool
	 */
	protected $isRunning = false;

	public function __construct() {
		$this->dispatchPluginEvent('onSpecConstruct');
	}

	/**
	 * @param string $pluginAccessName
	 * @return PluginInterface
	 */
	public function __get($pluginAccessName) {
		if ($pluginAccessName == '') {
			throw new Exception('Access to plugins by empty access name is denied');
		}
		
		$pluginClass = config::getRegisteredSpecPluginClassByAccessName($pluginAccessName);
		if ($pluginClass) {
			return $this->activatePluginByAccess($pluginClass);
		}
		
		throw new Exception('Undefined plugin with access name "' . $pluginAccessName . '" in "' . __CLASS__ . '" class');
	}

	/**
	 * @param string $pluginClass
	 * @return PluginInterface
	 */
	protected function activatePluginByAccess($pluginClass) {
		if (!array_key_exists($pluginClass, $this->activatedPlugins) || (string) $pluginClass::getActivateMoment() === 'everyAccess') {
			$this->activatedPlugins[$pluginClass] = new $pluginClass($this);
		}
		
		return $this->activatedPlugins[$pluginClass];
	}

	/**
	 * @param string $eventName
	 */
	protected function dispatchPluginEvent($eventName, array $arguments = array()) {
		foreach ($this->getPluginEventMethods($eventName) as $method) {
			$reflectionClass = new \ReflectionClass($method['class']);
			$reflectionMethod = $reflectionClass->getMethod($method['method']);
			$reflectionMethod->setAccessible(true);
			$reflectionMethod->invokeArgs($this->activatePluginByAccess($method['class']), $arguments);
		}
	}

	/**
	 * @param string $eventName
	 * @return array
	 */
	protected function getPluginEventMethods($eventName) {
		$methods = array();
		foreach (config::getRegisteredSpecPlugins() as $pluginClass) {
			foreach ((array) $pluginClass::getEventListeners() as $eventListener) {
				if ((string) $eventListener['event'] === (string) $eventName) {
					$methods[] = array(
						'class' => $pluginClass,
						'method' => $eventListener['method'],
						'order' => $eventListener['order'],
					);
				}
			}
		}
		
		$this->usortWithOriginalSequencePreserving($methods, function($a, $b) {
			if ($a['order'] == $b['order']) {
				return 0;
			}
			
			return ($a['order'] < $b['order'] ? -1 : 1);
		});
		
		return $methods;
	}

	/**
	 * @param array $array
	 * @param callable $cmpFunction
	 * @param bool $reverseEqualElementSequence
	 */
	protected function usortWithOriginalSequencePreserving(&$array, $cmpFunction, $reverseEqualElementSequence = false) {
		$indexes = array();
		$num = 0;
		foreach ($array as $key => $value) {
			$indexes[$key] = $num;
			$num++;
		}
		
		uksort($array, function($keyA, $keyB) use($array, &$indexes, &$cmpFunction, &$reverseEqualElementSequence) {
			$result = $cmpFunction($array[$keyA], $array[$keyB]);
			
			// Keep equal elements in original sequence
			if ($result == 0) {
				// Equal indexes are not existed
				if ($reverseEqualElementSequence) {
					return ($indexes[$keyA] < $indexes[$keyB] ? 1 : -1);
				} else {
					return ($indexes[$keyA] < $indexes[$keyB] ? -1 : 1);
				}
			}
			
			return $result;
		});
	}

/**/

	public function enable() {
		$this->handleModifyDeny(__FUNCTION__);
		$this->isEnabled = true;
	}

	public function disable() {
		$this->handleModifyDeny(__FUNCTION__);
		$this->isEnabled = false;
	}

	/**
	 * @return bool
	 */
	public function isEnabled() {
		return $this->isEnabled;
	}

/**/

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->handleModifyDeny(__FUNCTION__);
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function isAnonymous() {
		return ($this->getName() === null && $this->childSpecs);
	}

/**/

	/**
	 * @return SpecInterface[]
	 */
	public function getParentSpecs() {
		return $this->parentSpecs;
	}
	
	/**
	 * @return bool
	 */
	public function hasParentSpec(SpecInterface $spec) {
		if (array_search($spec, $this->parentSpecs, true) !== false) {
			return true;
		} else {
			return false;
		}
	}

	public function bindParentSpec(SpecInterface $spec) {
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!$this->hasParentSpec($spec)) {
			$this->parentSpecs[] = $spec;
		}
		
		if (!$spec->hasChildSpec($this)) {
			$spec->bindChildSpec($this);
		}
	}
	
	public function unbindParentSpec(SpecInterface $spec) {
		$this->handleModifyDeny(__FUNCTION__);
		
		$parentSpecKey = array_search($spec, $this->parentSpecs, true);
		if ($parentSpecKey !== false) {
			unset($this->parentSpecs[$parentSpecKey]);
			$this->parentSpecs = array_values($this->parentSpecs);
		}
		
		if ($spec->hasChildSpec($this)) {
			$spec->unbindChildSpec($this);
		}
	}

	public function unbindAllParentSpecs() {
		$this->handleModifyDeny(__FUNCTION__);
		
		foreach ($this->parentSpecs as $spec) {
			$this->unbindParentSpec($spec);
		}
		
		$this->parentSpecs = array();
	}
	
/**/
	
	/**
	 * @return SpecInterface[]
	 */
	public function getChildSpecs() {
		return $this->childSpecs;
	}

	/**
	 * @return bool
	 */
	public function hasChildSpec(SpecInterface $spec) {
		if (array_search($spec, $this->childSpecs, true) !== false) {
			return true;
		} else {
			return false;
		}
	}
	
	public function bindChildSpec(SpecInterface $spec) {
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!$this->hasChildSpec($spec)) {
			$this->childSpecs[] = $spec;
		}
		
		if (!$spec->hasParentSpec($this)) {
			$spec->bindParentSpec($this);
		}
	}
	
	public function unbindChildSpec(SpecInterface $spec) {
		$this->handleModifyDeny(__FUNCTION__);
		
		$childSpecKey = array_search($spec, $this->childSpecs, true);
		if ($childSpecKey !== false) {
			unset($this->childSpecs[$childSpecKey]);
			$this->childSpecs = array_values($this->childSpecs);
		}
		
		if ($spec->hasParentSpec($this)) {
			$spec->unbindParentSpec($this);
		}
	}

	public function unbindAllChildSpecs() {
		$this->handleModifyDeny(__FUNCTION__);
		
		foreach ($this->childSpecs as $spec) {
			$this->unbindChildSpec($spec);
		}
		
		$this->childSpecs = array();
	}

/**/
	
	/**
	 * @return SpecInterface[]
	 */
	public function getAncestorRootSpecs() {
		$rootSpecs = array();
		
		foreach ($this->parentSpecs as $parentSpec) {
			if (!$parentSpec->getParentSpecs() && !in_array($parentSpec, $rootSpecs, true)) {
				$rootSpecs[] = $parentSpec;
			}
			
			foreach ($parentSpec->getAncestorRootSpecs() as $spec) {
				if (!in_array($spec, $rootSpecs, true)) {
					$rootSpecs[] = $spec;
				}
			}
		}
		
		return $rootSpecs;
	}
	
	/**
	 * @return SpecInterface[]
	 */
	public function getDescendantEndingSpecs() {
		$endingSpecs = array();
		foreach ($this->childSpecs as $childSpec) {
			if ($childSpec->getChildSpecs()) {
				$endingSpecs = array_merge($endingSpecs, $childSpec->getDescendantEndingSpecs());
			} else {
				$endingSpecs[] = $childSpec;
			}
		}
		
		return $endingSpecs;
	}
	
	/**
	 * @return null|SpecInterface
	 */
	public function getRunningParentSpec() {
		foreach ($this->parentSpecs as $parentSpec) {
			if ($parentSpec->isRunning()) {
				return $parentSpec;
			}
		}
		
		return null;
	}

	/**
	 * Return running ancestor specs from parent to root
	 * @return SpecInterface[]
	 */
	public function getRunningAncestorSpecs() {
		$ancestorSpecs = array();

		$parent = $this;
		while ($parent = $parent->getRunningParentSpec()) {
			$ancestorSpecs[] = $parent;
		}

		return $ancestorSpecs;
	}
	
	/**
	 * @return null|SpecInterface
	 */
	public function getRunningChildSpec() {
		foreach ($this->childSpecs as $childSpec) {
			if ($childSpec->isRunning()) {
				return $childSpec;
			}
		}
		
		return null;
	}
	
	/**
	 * @return null|SpecInterface
	 */
	public function getRunningDescendantEndingSpec() {
		foreach ($this->childSpecs as $childSpec) {
			if ($childSpec->isRunning()) {
				if ($childSpec->getChildSpecs()) {
					return $childSpec->getRunningDescendantEndingSpec();
				} else {
					return $childSpec;
				}
			}
		}
		
		return null;
	}
	
	/**
	 * @return SpecInterface[]
	 */
	public function getSpecsByRunId($runId) {
		if ($this->getParentSpecs()) {
			throw new Exception('Method "\\' . get_class($this) . '::' . __FUNCTION__ . '" should be called from root spec only');
		}
		
		$runId = trim($runId);
		
		if (!preg_match('/^r(_\d+)*$/s', $runId)) {
			throw new Exception('Incorrect run id "' . $runId . '" (id should be in format "r_<number>_<number>_...")');
		}

		$specs = array();
		$currentSpec = $this;
		$specs[] = $currentSpec;
		
		$runIdWithoutRoot = mb_substr($runId, 2, mb_strlen($runId, 'us-ascii'), 'us-ascii');
		if ($runIdWithoutRoot != '') {
			foreach (explode('_', $runIdWithoutRoot) as $num => $index) {
				$childSpecs = $currentSpec->getChildSpecs();

				if (array_key_exists($index, $childSpecs)) {
					$currentSpec = $childSpecs[$index];
					$specs[] = $currentSpec;
				} else {
					throw new Exception('Spec with index "' . $index . '" on "' . ($num + 2) . '" position of run id "' . $runId . '" is not exists');
				}
			}
		}
		
		return $specs;
	}

/**/

	/**
	 * @return null|ResultBufferInterface
	 */
	public function getResultBuffer() {
		return $this->resultBuffer;
	}

/**/

	/*
	 * format: <ancestor spec index in parent>x<next ancestor spec index in parent>x<etc.>
	 * example: "0x1x24"
	 * 
	 * @return string String in "US-ASCII" charset
	 */
	public function getRunId() {
		if (!$this->isRunning()) {
			throw new Exception('Call of "\\' . get_class($this) . '::' . __FUNCTION__ . '" method is available on run only');
		}
		
		$runId = '';
		
		$spec = $this;
		while ($runningParentSpec = $spec->getRunningParentSpec()) {
			foreach ($runningParentSpec->getChildSpecs() as $index => $specInParent) {
				if ($specInParent === $spec) {
					$runId = '_' . $index . $runId;
					break;
				}
			}
			
			$spec = $runningParentSpec;
		}
		
		return 'r' . $runId;
	}

	/**
	 * @return bool
	 */
	public function isRunning() {
		return $this->isRunning;
	}

	/**
	 * @return null|bool
	 */
	public function run() {
		$rootSpecs = $this->getAncestorRootSpecs();
		$runningParentSpec = $this->getRunningParentSpec();
		
		if (count($rootSpecs) > 1) {
			throw new Exception('Spec "' . $this->getName() . '" has more than one root ancestors, but for run needs only one general root');
		}
		
		if ($this->isRunning()) {
			throw new Exception('Spec "' . $this->getName() . '" is already running');
		}
			
		if ($runningParentSpec && $runningParentSpec->getRunningChildSpec()) {
			throw new Exception('Sibling spec of spec "' . $this->getName() . '" is already running');
		}
		
		if ($this->parentSpecs && !$runningParentSpec) {
			if ($rootSpecs[0]->isRunning()) {
				throw new Exception('Root spec of spec "' . $this->getName() . '" is already running');
			}
			
			$siblingSpecs = $this->getEnabledSiblingSpecsUpToRoot();
			
			foreach ($siblingSpecs as $spec) {
				$spec->disable();
			}
			
			$result = $rootSpecs[0]->run();
			
			foreach ($siblingSpecs as $spec) {
				$spec->enable();
			}
			
			return $result;
		}

		// Now (after foregoing checks) we knows that this spec is spec without parent or with running parent (and the 
		// parent for a while has no running children)
		
		if (!$this->parentSpecs) {
			$this->dispatchPluginEvent('onRootSpecRunBefore');
		}
		
		$this->isRunning = true;
		$this->dispatchPluginEvent('onSpecRunStart');
		
		if ($this->childSpecs) {
			$this->executeAsNotEndingSpec();
		} else {
			$this->executeAsEndingSpec();
		}
		
		$this->dispatchPluginEvent('onSpecRunFinish');
		$this->isRunning = false;
		
		if (!$this->parentSpecs) {
			$this->dispatchPluginEvent('onRootSpecRunAfter');
		}

		$result = $this->getResultBuffer()->getTotalResult();
		$this->resultBuffer = null;
		return $result;
	}
	
	protected function executeAsNotEndingSpec() {
		$resultBuffer = $this->createResultBuffer();
		
		foreach ($this->childSpecs as $childSpec) {
			if ($childSpec->isEnabled()) {
				$resultBuffer->addResult($childSpec->run(), $childSpec);
			}
		}
		
		$resultBuffer->lock();
		$this->resultBuffer = $resultBuffer;
	}
	
	protected function executeAsEndingSpec() {
		$this->resultBuffer = $this->createResultBuffer();
		$this->dispatchPluginEventAndCatchExceptions('onEndingSpecExecuteBefore');
		$this->dispatchPluginEventAndCatchExceptions('onEndingSpecExecute');
		$this->dispatchPluginEventAndCatchExceptions('onEndingSpecExecuteAfter');
	}

	/**
	 * @param string $eventName
	 */
	protected function dispatchPluginEventAndCatchExceptions($eventName, array $arguments = array()) {
		foreach ($this->getPluginEventMethods($eventName) as $method) {
			try {
				$reflectionClass = new \ReflectionClass($method['class']);
				$reflectionMethod = $reflectionClass->getMethod($method['method']);
				$reflectionMethod->setAccessible(true);
				$reflectionMethod->invokeArgs($this->activatePluginByAccess($method['class']), $arguments);
			} catch (BreakException $e) {
				// Just ignore special break exception
			} catch (\Exception $e) {
				$this->getResultBuffer()->addResult(false, $e);
			}
		}
	}

	/**
	 * @return ResultBufferInterface
	 */
	protected function createResultBuffer() {
		$resultBufferClass = config::getClassReplacement('\spectrum\core\ResultBuffer');
		return new $resultBufferClass($this);
	}

	/**
	 * @return SpecInterface[]
	 */
	protected function getEnabledSiblingSpecsUpToRoot() {
		$siblingSpecs = array();
		$notSiblingSpecs = array_merge(array($this), $this->getAncestorSpecs());
		$specsToWalk = array($this);
		while ($specsToWalk) {
			/** @var SpecInterface $spec */
			$spec = array_shift($specsToWalk);
			foreach ($spec->getParentSpecs() as $parentSpec) {
				if (!$parentSpec->isEnabled()) {
					continue;
				}
				
				$specsToWalk[] = $parentSpec;
				
				foreach ($parentSpec->getChildSpecs() as $childSpec) {
					if ($childSpec->isEnabled() && !in_array($childSpec, $siblingSpecs, true) && !in_array($childSpec, $notSiblingSpecs, true)) {
						$siblingSpecs[] = $childSpec;
					}
				}
			}
		}
		
		return $siblingSpecs;
	}

	/**
	 * @return SpecInterface[]
	 */
	protected function getAncestorSpecs() {
		$ancestorSpecs = array();
		$specsToWalk = $this->getParentSpecs();
		while ($specsToWalk) {
			/** @var SpecInterface $spec */
			$spec = array_shift($specsToWalk);
			$specsToWalk = array_merge($specsToWalk, $spec->getParentSpecs());
			$ancestorSpecs[] = $spec;
		}

		return $ancestorSpecs;
	}
	
/**/

	/**
	 * @param string $functionName
	 */
	protected function handleModifyDeny($functionName) {
		foreach (array_merge(array($this), $this->getAncestorRootSpecs()) as $spec) {
			/** @var SpecInterface $spec */
			if ($spec->isRunning()) {
				throw new Exception('Call of "\\' . get_class($this) . '::' . $functionName . '" method is forbidden on run');
			}
		}
	}
}