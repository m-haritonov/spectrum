<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core;
use spectrum\config;

/**
 * @property \spectrum\core\plugins\basePlugins\contexts\Contexts contexts
 * @property \spectrum\core\plugins\basePlugins\ErrorHandling errorHandling
 * @property \spectrum\core\plugins\basePlugins\reports\Reports reports
 * @property \spectrum\core\plugins\basePlugins\Matchers matchers
 * @property \spectrum\core\plugins\basePlugins\Messages messages
 * @property \spectrum\core\plugins\basePlugins\Test test
 */
class Spec implements SpecInterface
{
	/**
	 * @var array
	 */
	protected $activatedPlugins = array();

	/**
	 * @var bool
	 */
	protected $isEnabled = true;

	/**
	 * @var string
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
	 * @var ResultBufferInterface|null
	 */
	protected $resultBuffer;

	/**
	 * @var bool
	 */
	protected $isRunning = false;

	public function __construct()
	{
		$this->dispatchPluginEvent('onSpecConstruct');
	}

	public function __get($pluginAccessName)
	{
		if ($pluginAccessName == '')
			throw new Exception('Access to plugins by empty access name is deny');
		
		$pluginClass = config::getRegisteredSpecPluginClassByAccessName($pluginAccessName);
		if ($pluginClass)
			return $this->activatePluginByAccess($pluginClass);
		
		throw new Exception('Undefined plugin with access name "' . $pluginAccessName . '" in "' . __CLASS__ . '" class');
	}

	protected function activatePluginByAccess($pluginClass)
	{
		if (!array_key_exists($pluginClass, $this->activatedPlugins) || $pluginClass::getActivateMoment() == 'everyAccess')
			$this->activatedPlugins[$pluginClass] = new $pluginClass($this);
		
		return $this->activatedPlugins[$pluginClass];
	}
	
	protected function dispatchPluginEvent($eventName, array $arguments = array())
	{
		foreach ($this->getPluginEventMethods($eventName) as $method)
		{
			$reflectionClass = new \ReflectionClass($method['class']);
			$reflectionMethod = $reflectionClass->getMethod($method['method']);
			$reflectionMethod->setAccessible(true);
			$reflectionMethod->invokeArgs($this->activatePluginByAccess($method['class']), $arguments);
		}
	}
	
	protected function getPluginEventMethods($eventName)
	{
		$methods = array();
		foreach (config::getRegisteredSpecPlugins() as $pluginClass)
		{
			foreach ((array) $pluginClass::getEventListeners() as $eventListener)
			{
				if ($eventListener['event'] == $eventName)
				{
					$methods[] = array(
						'class' => $pluginClass,
						'method' => $eventListener['method'],
						'order' => $eventListener['order'],
					);
				}
			}
		}
		
		$this->usortWithOriginalSequencePreserving($methods, function($a, $b){
			if ($a['order'] == $b['order'])
				return 0; 
			
			return ($a['order'] < $b['order'] ? -1 : 1);
		});
		
		return $methods;
	}
	
	protected function usortWithOriginalSequencePreserving(&$array, $cmpFunction, $reverseEqualElementSequence = false)
	{
		$indexes = array();
		$num = 0;
		foreach ($array as $key => $value)
		{
			$indexes[$key] = $num;
			$num++;
		}
		
		uksort($array, function($keyA, $keyB) use($array, &$indexes, &$cmpFunction, &$reverseEqualElementSequence)
		{
			$result = $cmpFunction($array[$keyA], $array[$keyB]);
			
			// Keep equal elements in original sequence
			if ($result == 0)
			{
				// Equal indexes are not existed
				if ($reverseEqualElementSequence)
					return ($indexes[$keyA] < $indexes[$keyB] ? 1 : -1);
				else
					return ($indexes[$keyA] < $indexes[$keyB] ? -1 : 1);
			}
			
			return $result;
		});
	}

/**/

	public function enable()
	{
		$this->handleModifyDeny(__FUNCTION__);
		$this->isEnabled = true;
	}

	public function disable()
	{
		$this->handleModifyDeny(__FUNCTION__);
		$this->isEnabled = false;
	}

	public function isEnabled()
	{
		return $this->isEnabled;
	}

/**/

	public function setName($name)
	{
		$this->handleModifyDeny(__FUNCTION__);
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}
	

	public function isAnonymous()
	{
		return ((string) $this->getName() === '' && $this->childSpecs);
	}

/**/
	
	/*
	 * format: <ancestor spec index in parent>x<next ancestor spec index in parent>x<etc.>
	 * example: "0x1x24"
	 * 
	 * @return string String in "us-ascii" charset
	 */
	public function getSpecId()
	{
		// TODO: id should be unique for every running spec (for every running ancestor stack)
		return 'spec' . spl_object_hash($this);
		
/*		$stack = $this->getRunningAncestorSpecs();
		$stack[] = $this;

		$uid = '0x';
		foreach ($stack as $spec)
		{
			if ($spec->getParentSpec())
			{
				foreach ($spec->getParentSpec()->getSpecs() as $index => $specInParent)
				{
					if ($specInParent === $spec)
					{
						$uid .= (int) $index . 'x';
						break;
					}
				}
			}
		}

		return mb_substr($uid, 0, -1, 'us-ascii');*/
	}

	public function getSpecById($specId)
	{
/*		$specId = trim($specId);
		
		if (!preg_match('/^0(x\d+)*$/s', $specId))
			throw new Exception('Incorrect spec id "' . $specId . '" (id should be in format like "0x4" and first index in id should be "0")');

		$spec = null;
		foreach ($this->parseSpecIndexesInSpecId($specId) as $num => $specIndex)
		{
			if ($num == 0)
				$spec = $this->getRootSpec();
			else
				$spec = $spec->getChildSpecByIndex($specIndex);

			if (!$spec)
				throw new Exception('Incorrect spec id "' . $specId . '" (spec with index "' . $specIndex . '" on "' . ($num + 1) . '" position in id not exists)');
		}

		return $spec;*/
	}

	protected function parseSpecIndexesInSpecId($specId)
	{
		$specId = trim($specId);

		if (preg_match('/^spec(\d+(x\d+)*)/is', $specId, $matches))
			return explode('x', $matches[1]);
		else
			return array();
	}
	
/**/
	
	public function getParentSpecs()
	{
		return $this->parentSpecs;
	}
	
	public function hasParentSpec(SpecInterface $spec)
	{
		if (array_search($spec, $this->parentSpecs, true) !== false)
			return true;
		else
			return false;
	}

	public function bindParentSpec(SpecInterface $spec)
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!$this->hasParentSpec($spec))
			$this->parentSpecs[] = $spec;
		
		if (!$spec->hasChildSpec($this))
			$spec->bindChildSpec($this);
	}
	
	public function unbindParentSpec(SpecInterface $spec)
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		$parentSpecKey = array_search($spec, $this->parentSpecs, true);
		if ($parentSpecKey !== false)
		{
			unset($this->parentSpecs[$parentSpecKey]);
			$this->parentSpecs = array_values($this->parentSpecs);
		}
		
		if ($spec->hasChildSpec($this))
			$spec->unbindChildSpec($this);
	}

	public function unbindAllParentSpecs()
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		foreach ($this->parentSpecs as $spec)
			$this->unbindParentSpec($spec);
		
		$this->parentSpecs = array();
	}
	
/**/
	
	public function getChildSpecs()
	{
		return $this->childSpecs;
	}

	public function hasChildSpec(SpecInterface $spec)
	{
		if (array_search($spec, $this->childSpecs, true) !== false)
			return true;
		else
			return false;
	}
	
	public function bindChildSpec(SpecInterface $spec)
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!$this->hasChildSpec($spec))
			$this->childSpecs[] = $spec;
		
		if (!$spec->hasParentSpec($this))
			$spec->bindParentSpec($this);
	}
	
	public function unbindChildSpec(SpecInterface $spec)
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		$childSpecKey = array_search($spec, $this->childSpecs, true);
		if ($childSpecKey !== false)
		{
			unset($this->childSpecs[$childSpecKey]);
			$this->childSpecs = array_values($this->childSpecs);
		}
		
		if ($spec->hasParentSpec($this))
			$spec->unbindParentSpec($this);
	}

	public function unbindAllChildSpecs()
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		foreach ($this->childSpecs as $spec)
			$this->unbindChildSpec($spec);
		
		$this->childSpecs = array();
	}

/**/
	
	public function getRootSpecs()
	{
		$rootSpecs = array();
		
		$parentSpecs = $this->parentSpecs;
		foreach ($parentSpecs as $parentSpec)
		{
			if (!$parentSpec->getParentSpecs() && !in_array($parentSpec, $rootSpecs, true))
				$rootSpecs[] = $parentSpec;
			
			foreach ($parentSpec->getRootSpecs() as $spec)
			{
				if (!in_array($spec, $rootSpecs, true))
					$rootSpecs[] = $spec;
			}
		}
		
		return $rootSpecs;
	}
	
	public function getEndingSpecs()
	{
		$endingSpecs = array();
		foreach ($this->childSpecs as $childSpec)
		{
			if ($childSpec->getChildSpecs())
				$endingSpecs = array_merge($endingSpecs, $childSpec->getEndingSpecs());
			else
				$endingSpecs[] = $childSpec;
		}
		
		return $endingSpecs;
	}
	
	public function getRunningParentSpec()
	{
		foreach ($this->parentSpecs as $parentSpec)
		{
			if ($parentSpec->isRunning())
				return $parentSpec;
		}
		
		return null;
	}

	/**
	 * Return running ancestor specs from parent to root
	 */
	public function getRunningAncestorSpecs()
	{
		$ancestorSpecs = array();

		$parent = $this->getRunningParentSpec();
		while ($parent)
		{
			$ancestorSpecs[] = $parent;
			$parent = $parent->getRunningParentSpec();
		}

		return $ancestorSpecs;
	}
	
	public function getRunningChildSpec()
	{
		foreach ($this->childSpecs as $childSpec)
		{
			if ($childSpec->isRunning())
				return $childSpec;
		}
		
		return null;
	}
	
	public function getRunningEndingSpec()
	{
		foreach ($this->childSpecs as $childSpec)
		{
			if ($childSpec->isRunning())
			{
				if ($childSpec->getChildSpecs())
					return $childSpec->getRunningEndingSpec();
				else
					return $childSpec;
			}
		}
		
		return null;
	}

/**/

	public function getResultBuffer()
	{
		return $this->resultBuffer;
	}

/**/

	public function isRunning()
	{
		return $this->isRunning;
	}
	
	public function run()
	{
		$rootSpecs = $this->getRootSpecs();
		$runningParentSpec = $this->getRunningParentSpec();
		
		if (count($rootSpecs) > 1)
			throw new Exception('Spec "' . $this->getName() . '" has more than one root ancestors, but for run needs only one general root');
		
		if ($this->isRunning())
			throw new Exception('Spec "' . $this->getName() . '" is already running');
			
		if ($runningParentSpec && $runningParentSpec->getRunningChildSpec())
			throw new Exception('Sibling spec of spec "' . $this->getName() . '" is already running');
		
		if ($this->parentSpecs && !$runningParentSpec)
		{
			if ($rootSpecs[0]->isRunning())
				throw new Exception('Root spec of spec "' . $this->getName() . '" is already running');
			
			$siblingSpecs = $this->getEnabledSiblingSpecsUpToRoot();
			
			foreach ($siblingSpecs as $spec)
				$spec->disable();
			
			$result = $rootSpecs[0]->run();
			
			foreach ($siblingSpecs as $spec)
				$spec->enable();
			
			return $result;
		}

		// Now (after foregoing checks) we knows that this spec is spec without parent or with running parent (and the 
		// parent for a while has no running children)
		
		if (!$this->parentSpecs)
			$this->dispatchPluginEvent('onRootSpecRunBefore');
		
		$this->isRunning = true;
		$this->dispatchPluginEvent('onSpecRunStart');
		
		if ($this->childSpecs)
			$this->executeAsNotEndingSpec();
		else
			$this->executeAsEndingSpec();
		
		$this->dispatchPluginEvent('onSpecRunFinish');
		$this->isRunning = false;
		
		if (!$this->parentSpecs)
			$this->dispatchPluginEvent('onRootSpecRunAfter');

		$result = $this->getResultBuffer()->getTotalResult();
		$this->resultBuffer = null;
		return $result;
	}
	
	protected function executeAsNotEndingSpec()
	{
		$resultBuffer = $this->createResultBuffer();
		
		foreach ($this->childSpecs as $childSpec)
		{
			if ($childSpec->isEnabled())
				$resultBuffer->addResult($childSpec->run(), $childSpec);
		}
		
		$resultBuffer->lock();
		$this->resultBuffer = $resultBuffer;
	}
	
	protected function executeAsEndingSpec()
	{
		$this->resultBuffer = $this->createResultBuffer();
		$this->dispatchPluginEventAndCatchExceptions('onEndingSpecExecuteBefore');
		$this->dispatchPluginEventAndCatchExceptions('onEndingSpecExecute');
		$this->dispatchPluginEventAndCatchExceptions('onEndingSpecExecuteAfter');
	}
	
	protected function dispatchPluginEventAndCatchExceptions($eventName, array $arguments = array())
	{
		foreach ($this->getPluginEventMethods($eventName) as $method)
		{
			try
			{
				$reflectionClass = new \ReflectionClass($method['class']);
				$reflectionMethod = $reflectionClass->getMethod($method['method']);
				$reflectionMethod->setAccessible(true);
				$reflectionMethod->invokeArgs($this->activatePluginByAccess($method['class']), $arguments);
			}
			catch (BreakException $e)
			{
				// Just ignore special break exception
			}
			catch (\Exception $e)
			{
				$this->getResultBuffer()->addResult(false, $e);
			}
		}
	}

	/**
	 * @return ResultBufferInterface
	 */
	protected function createResultBuffer()
	{
		$resultBufferClass = config::getResultBufferClass();
		return new $resultBufferClass($this);
	}
	
	protected function getEnabledSiblingSpecsUpToRoot()
	{
		$siblingSpecs = array();
		$notSiblingSpecs = array_merge(array($this), $this->getAncestorSpecs());
		$specsToWalk = array($this);
		while ($specsToWalk)
		{
			$spec = array_shift($specsToWalk);
			foreach ($spec->getParentSpecs() as $parentSpec)
			{
				if (!$parentSpec->isEnabled())
					continue;
				
				$specsToWalk[] = $parentSpec;
				
				foreach ($parentSpec->getChildSpecs() as $childSpec)
				{
					if ($childSpec->isEnabled() && !in_array($childSpec, $siblingSpecs, true) && !in_array($childSpec, $notSiblingSpecs, true))
						$siblingSpecs[] = $childSpec;
				}
			}
		}
		
		return $siblingSpecs;
	}
	
	protected function getAncestorSpecs()
	{
		$ancestorSpecs = array();
		$specsToWalk = $this->getParentSpecs();
		while ($specsToWalk)
		{
			$spec = array_shift($specsToWalk);
			$specsToWalk = array_merge($specsToWalk, $spec->getParentSpecs());
			$ancestorSpecs[] = $spec;
		}

		return $ancestorSpecs;
	}
	
/**/

	protected function handleModifyDeny($functionName)
	{
		foreach (array_merge(array($this), $this->getRootSpecs()) as $spec)
		{
			if ($spec->isRunning())
				throw new Exception('Call of "\\' . get_class($this) . '::' . $functionName . '" method is forbidden on run');
		}
	}
}