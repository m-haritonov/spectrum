<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;
use spectrum\config;

/**
 * @property \spectrum\core\plugins\basePlugins\reports\Reports reports
 * @property \spectrum\core\plugins\basePlugins\Contexts contexts
 * @property \spectrum\core\plugins\basePlugins\ErrorHandling errorHandling
 * @property \spectrum\core\plugins\basePlugins\TestFunction testFunction
 * @property \spectrum\core\plugins\basePlugins\Matchers matchers
 * @property \spectrum\core\plugins\basePlugins\Messages messages
 * @property \spectrum\core\plugins\basePlugins\Output output
 */
class Spec implements SpecInterface
{
	protected $name;
	protected $isEnabled = true;
	protected $isEnabledTemporarily = null;
	
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
	protected $isRunning = false;
	
	protected $activatedPlugins = array();

	public function __construct()
	{
		foreach (config::getRegisteredSpecPlugins() as $pluginClass)
		{
			if ($pluginClass::getActivateMoment() == 'specConstruct')
				$this->activatedPlugins[$pluginClass] = new $pluginClass($this);
		}
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
			foreach ($pluginClass::getEventListeners() as $eventListener)
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
		
		usort($methods, function($a, $b)
		{
			if ($a['order'] == $b['order'])
				return 0;
			
			return ($a['order'] < $b['order']) ? -1 : 1;
		});
		
		return $methods;
	}

/**/

	public function enable()
	{
		$this->handleModifyDeny(__FUNCTION__);
		$this->isEnabled = true;
		$this->isEnabledTemporarily = null;
	}

	public function disable()
	{
		$this->handleModifyDeny(__FUNCTION__);
		$this->isEnabled = false;
		$this->isEnabledTemporarily = null;
	}

	public function isEnabled()
	{
		if ($this->isEnabledTemporarily !== null)
			return $this->isEnabledTemporarily;
		else
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
		return ($this->getName() == '' && $this->childSpecs);
	}

/**/
	
	/*
	 * format: <ancestor spec index in parent>x<next ancestor spec index in parent>x<etc.>
	 * example: "0x1x24"
	 */
	public function getSpecId()
	{
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

		return mb_substr($uid, 0, -1);*/
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
		{
			$this->parentSpecs[] = $spec;
			$spec->childSpecs[] = $this;
		}
	}
	
	public function unbindParentSpec(SpecInterface $spec)
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		// Remove parent spec from this parent specs list
		$parentSpecKey = array_search($spec, $this->parentSpecs, true);
		if ($parentSpecKey !== false)
		{
			unset($this->parentSpecs[$parentSpecKey]);
			$this->parentSpecs = array_values($this->parentSpecs);
		}
		
		// Remove this spec from child specs list of parent spec
		$thisSpecKey = array_search($this, $spec->childSpecs, true);
		if ($thisSpecKey !== false)
		{
			unset($spec->childSpecs[$thisSpecKey]);
			$spec->childSpecs = array_values($spec->childSpecs);
		}
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
	
	/**
	 * Return all find specs with same name.
	 * @return array
	 */
	public function getChildSpecsByName($name)
	{
		$findSpecs = array();

		foreach ($this->childSpecs as $spec)
		{
			if ((string) $spec->getName() === (string) $name)
				$findSpecs[] = $spec;
		}

		return $findSpecs;
	}

	public function getChildSpecByNumber($number)
	{
		$number--;
		if (array_key_exists($number, $this->childSpecs))
			return $this->childSpecs[$number];
		else
			return null;
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
		{
			$this->childSpecs[] = $spec;
			$spec->parentSpecs[] = $this;
		}
	}
	
	public function unbindChildSpec(SpecInterface $spec)
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		// Remove child spec from this child specs list
		$childSpecKey = array_search($spec, $this->childSpecs, true);
		if ($childSpecKey !== false)
		{
			unset($this->childSpecs[$childSpecKey]);
			$this->childSpecs = array_values($this->childSpecs);
		}
		
		// Remove this spec from parent specs list of child spec
		$thisSpecKey = array_search($this, $spec->parentSpecs, true);
		if ($thisSpecKey !== false)
		{
			unset($spec->parentSpecs[$thisSpecKey]);
			$this->parentSpecs = array_values($this->parentSpecs);
		}
	}

	public function unbindAllChildSpecs()
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		foreach ($this->childSpecs as $spec)
			$this->unbindChildSpec($spec);
		
		$this->childSpecs = array();
	}

/**/
	
	public function isRoot()
	{
		return !$this->parentSpecs;
	}
	
	public function getRootSpec()
	{
		$rootSpec = $this;
		while (true)
		{
			$parentSpecs = $rootSpec->parentSpecs;
			if ($parentSpecs)
				$rootSpec = $parentSpecs[0];
			else
				break;
		}
		
		return $rootSpec;
	}
	
	public function getRootSpecs()
	{
		$rootSpecs = array();
		
		$parentSpecs = $this->parentSpecs;
		foreach ($parentSpecs as $parentSpec)
		{
			if ($parentSpec->isRoot() && !in_array($parentSpec, $rootSpecs, true))
				$rootSpecs[] = $parentSpec;
			
			foreach ($parentSpec->getRootSpecs() as $spec)
			{
				if (!in_array($spec, $rootSpecs, true))
					$rootSpecs[] = $spec;
			}
		}
		
		return $rootSpecs;
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
	
	/**
	 * Return deepest spec from running spec stack. Use it for get current running spec through root spec.
	 */
	public function getDeepestRunningSpec()
	{
		foreach ($this->childSpecs as $childSpec)
		{
			if ($childSpec->isRunning())
				return $childSpec->getDeepestRunningSpec();
		}
		
		if ($this->isRunning())
			return $this;
		else
			return null;
	}
	
/**/
	
	public function getResultBuffer()
	{
		return $this->resultBuffer;
	}
	
	public function isRunning()
	{
		return $this->isRunning;
	}
	
	public function run()
	{
		if (count($this->getRootSpecs()) > 1)
			throw new Exception('Spec "' . $this->getName() . '" has more than one root ancestors, but for run needs only one general root');
		
		if ($this->getRootSpec()->isRunning())
			throw new Exception('Spec tree already running');
		
		if (!$this->isRoot() && !$this->getRunningParentSpec())
		{
			$this->disableSiblingSpecsTemporarilyUpToRoot();
			$result = $this->getRootSpec()->run();
			$this->resetSiblingSpecsTemporarilyUpToRoot();
			return $result;
		}
		else
			return $this->runSelf();
	}
	
	protected function runSelf()
	{
		if (count($this->getRootSpecs()) > 1)
			throw new Exception('Spec "' . $this->getName() . '" has more than one root ancestors, but for run needs only one general root');
		
		$this->dispatchPluginEvent('onSpecRunBefore');
		$this->isRunning = true;
		$this->dispatchPluginEvent('onSpecRunInit');

		if ($this->childSpecs)
		{
			$results = array();
			foreach ($this->childSpecs as $childSpec)
			{
				if ($childSpec->isEnabled())
					$results[] = $childSpec->runSelf();
			}
			
			$result = $this->calculateChildSpecsRunTotalResult($results);
		}
		else
		{
			$this->executeEndingSpec();
			$result = $this->getResultBuffer()->getTotalResult();
		}

		$this->dispatchPluginEvent('onSpecRunFinish', array($result));
		$this->isRunning = false;
		$this->dispatchPluginEvent('onSpecRunAfter', array($result));
		return $result;
	}
	
	protected function executeEndingSpec()
	{
		$resultBufferClass = config::getResultBufferClass();
		$this->resultBuffer = new $resultBufferClass($this);
		
		try
		{
			$this->dispatchPluginEvent('onEndingSpecExecuteBefore');
			$this->dispatchPluginEvent('onEndingSpecExecute');
			$this->dispatchPluginEvent('onEndingSpecExecuteAfter');
		}
		catch (ExceptionBreak $e)
		{
			// Just ignore special break exceptions
		}
		catch (\Exception $e)
		{
			$this->getResultBuffer()->addFailResult($e);
		}
	}
	
	protected function disableSiblingSpecsTemporarilyUpToRoot()
	{
		$this->isEnabledTemporarily = true;
		
		$parentSpecs = $this->parentSpecs;
		foreach ($parentSpecs as $parentSpec)
		{
			foreach ($parentSpec->childSpecs as $spec)
			{
				if ($spec->isEnabledTemporarily === null)
					$spec->isEnabledTemporarily = false;
			}
			
			$parentSpec->disableSiblingSpecsTemporarilyUpToRoot();
		}
	}
	
	protected function resetSiblingSpecsTemporarilyUpToRoot()
	{
		$this->isEnabledTemporarily = null;
		
		$parentSpecs = $this->parentSpecs;
		foreach ($parentSpecs as $parentSpec)
		{
			foreach ($parentSpec->childSpecs as $spec)
				$spec->isEnabledTemporarily = null;
			
			$parentSpec->resetSiblingSpecsTemporarilyUpToRoot();
		}
	}
	
	protected function calculateChildSpecsRunTotalResult(array $results)
	{
		$hasEmpty = false;
		foreach ($results as $result)
		{
			// Check all results to false
			if ($result === false)
				return false;
			else if ($result === null)
				$hasEmpty = true;
		}

		if ($hasEmpty)
			return null;
		else if (count($results))
			return true;
		else
			return null;
	}
	
/**/

	protected function handleModifyDeny($functionName)
	{
		if ($this->getRootSpec()->isRunning())
			throw new Exception('Call of "' . $functionName . '" method is deny on running');
	}
}