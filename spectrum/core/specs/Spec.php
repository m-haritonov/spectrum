<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs;
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
	protected $activatedPlugins = array();
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

	public function __construct()
	{
		foreach (config::getAllRegisteredSpecPlugins() as $pluginClass)
		{
			if ($pluginClass::getActivateMoment() == 'specConstruct')
				$this->activatedPlugins[$pluginClass::getAccessName()] = new $pluginClass($this);
		}
	}

	public function __get($pluginAccessName)
	{
		$pluginClass = config::getRegisteredSpecPluginClassByAccessName($pluginAccessName);

		if ($pluginClass::getActivateMoment() == 'everyAccess' || !array_key_exists($pluginAccessName, $this->activatedPlugins))
			$this->activatedPlugins[$pluginClass::getAccessName()] = new $pluginClass($this);

		return $this->activatedPlugins[$pluginAccessName];
	}

	protected function dispatchPluginEvent($eventName, array $arguments = array())
	{
		foreach ($this->getPluginMethodsToEventDispatch($eventName) as $method)
		{
			$plugin = $this->{$method['accessName']};
			$reflectionClass = new \ReflectionClass($plugin);
			$reflectionMethod = $reflectionClass->getMethod($method['method']);
			$reflectionMethod->setAccessible(true);
			$reflectionMethod->invokeArgs($plugin, $arguments);
		}
	}
	
	protected function getPluginMethodsToEventDispatch($eventName)
	{
		$methods = array();
		foreach (config::getAllRegisteredSpecPlugins() as $pluginClass)
		{
			foreach ($pluginClass::getEventListeners() as $eventListener)
			{
				if ($eventListener['event'] == $eventName)
				{
					$methods[] = array(
						'accessName' => $pluginClass::getAccessName(),
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
		$this->handleModifyDeny();
		$this->isEnabled = true;
		$this->isEnabledTemporarily = null;
	}

	public function disable()
	{
		$this->handleModifyDeny();
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
		$this->handleModifyDeny();
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}
	

	public function isAnonymous()
	{
		return ($this->getName() == '' && $this->getChildSpecs());
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
	
	public function isRoot()
	{
		return !$this->getParentSpecs();
	}
	
	public function getRootSpec()
	{
		$rootSpec = $this;
		while (true)
		{
			$parentSpecs = $rootSpec->getParentSpecs();
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
		
		$parentSpecs = $this->getParentSpecs();
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
		foreach ($this->getParentSpecs() as $parentSpec)
		{
			if ($parentSpec->isRunning())
				return $parentSpec;
		}
		
		return null;
	}

	public function getRunningAncestorSpecs()
	{
		$runningAncestors = array();

		$parent = $this;
		while ($parent = $parent->getRunningParentSpec())
			$runningAncestors[] = $parent;

		return array_reverse($runningAncestors);
	}
	
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
		$this->handleModifyDeny();
		
		if (!$this->hasParentSpec($spec))
		{
			$this->parentSpecs[] = $spec;
			$spec->childSpecs[] = $this;
		}
	}
	
	public function unbindParentSpec(SpecInterface $spec)
	{
		$this->handleModifyDeny();
		
		// Remove parent spec from this parent specs list
		$parentSpecKey = array_search($spec, $this->parentSpecs, true);
		if ($parentSpecKey !== false)
			unset($this->parentSpecs[$parentSpecKey]);
		
		// Remove this spec from child specs list of parent spec
		$thisSpecKey = array_search($this, $spec->childSpecs, true);
		if ($thisSpecKey !== false)
			unset($spec->childSpecs[$thisSpecKey]);
	}

	public function unbindAllParentSpecs()
	{
		$this->handleModifyDeny();
		
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

		foreach ($this->childSpecs as $index => $spec)
		{
			if ($spec->getName() == $name)
				$findSpecs[$index] = $spec;
		}

		return $findSpecs;
	}

	public function getChildSpecByIndex($index)
	{
		if (array_key_exists($index, $this->childSpecs))
			return $this->childSpecs[$index];
		else
			return null;
	}

	/**
	 * Return deepest spec from running specs stack. 
	 */
	public function getDeepestRunningSpec()
	{
		foreach ($this->getChildSpecs() as $childSpec)
		{
			if ($childSpec->isRunning())
				return $childSpec->getDeepestRunningSpec();
		}
		
		if ($this->isRunning())
			return $this;
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
		$this->handleModifyDeny();
		
		if (!$this->hasChildSpec($spec))
		{
			$this->childSpecs[] = $spec;
			$spec->parentSpecs[] = $this;
		}
	}
	
	public function unbindChildSpec(SpecInterface $spec)
	{
		$this->handleModifyDeny();
		
		// Remove child spec from this child specs list
		$childSpecKey = array_search($spec, $this->childSpecs, true);
		if ($childSpecKey !== false)
			unset($this->childSpecs[$childSpecKey]);
		
		// Remove this spec from parent specs list of child spec
		$thisSpecKey = array_search($this, $spec->parentSpecs, true);
		if ($thisSpecKey !== false)
			unset($spec->parentSpecs[$thisSpecKey]);
	}

	public function unbindAllChildSpecs()
	{
		$this->handleModifyDeny();
		
		foreach ($this->childSpecs as $spec)
			$this->unbindChildSpec($spec);
		
		$this->childSpecs = array();
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
		if ($this->getRootSpec()->isRunning())
			throw new Exception('Spec tree already running');
		
		if (count($this->getRootSpecs()) > 1)
			throw new Exception('Spec "' . $this->getName() . '" has more than one root ancestors, but for run needs only one general root');
		
		if (!$this->getRunningParentSpec() && !$this->isRoot())
			return $this->runSelfThroughAncestors();
		else
			return $this->runSelfDirect();
	}
	
	protected function runSelfThroughAncestors()
	{
		$this->disableSiblingSpecsTemporarilyUpToRoot();
		$result = $this->getRootSpec()->run();
		$this->resetSiblingSpecsTemporarilyUpToRoot();
		
		return $result;
	}
	
	protected function runSelfDirect()
	{
		$this->dispatchPluginEvent('onSpecRunBefore');
		$this->isRunning = true;
		$this->dispatchPluginEvent('onSpecRunInit');

		if ($this->getChildSpecs())
		{
			$results = array();
			foreach ($this->getChildSpecs() as $childSpec)
			{
				if ($childSpec->isEnabled())
					$results[] = $childSpec->run();
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
		
		$parentSpecs = $this->getParentSpecs();
		foreach ($parentSpecs as $parentSpec)
		{
			foreach ($parentSpec->getChildSpecs() as $spec)
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
		
		$parentSpecs = $this->getParentSpecs();
		foreach ($parentSpecs as $parentSpec)
		{
			foreach ($parentSpec->getChildSpecs() as $spec)
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

	protected function handleModifyDeny()
	{
		if ($this->getRootSpec()->isRunning())
			throw new Exception('Modify specs when spec tree is running deny');
	}
}