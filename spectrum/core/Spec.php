<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

/**
 * @property \spectrum\core\plugins\basePlugins\Matchers matchers
 * @property \spectrum\core\plugins\basePlugins\worldCreators\Builders builders
 * @property \spectrum\core\plugins\basePlugins\worldCreators\Destroyers destroyers
 * @property \spectrum\core\plugins\basePlugins\Selector selector
 * @property \spectrum\core\plugins\basePlugins\Identify identify
 * @property \spectrum\core\plugins\basePlugins\ErrorHandling errorHandling
 * @property \spectrum\core\plugins\basePlugins\Output output
 * @property \spectrum\core\plugins\basePlugins\Messages messages
 * @property \spectrum\core\plugins\basePlugins\Patterns patterns
 * @property \spectrum\reports\Plugin reports
 */
abstract class Spec implements SpecInterface
{
	protected $name;
	/**
	 * @var SpecInterface
	 */
	protected $parent;
	private $isEnabled = true;
	private $isEnabledTemporarily = null;
	protected $activatedPlugins = array();
	protected $isRunning = false;

	public function __construct()
	{
		$class = Config::getPluginsManagerClass();
		foreach ($class::getRegisteredPlugins() as $pluginAccessName => $plugin)
		{
			if ($plugin['activateMoment'] == 'whenConstructOnce')
				$this->activatePlugin($pluginAccessName);
		}
	}

	public function __get($pluginAccessName)
	{
		return $this->callPlugin($pluginAccessName);
	}
	
	public function callPlugin($pluginAccessName)
	{
		$manager = Config::getPluginsManagerClass();
		$plugin = $manager::getRegisteredPlugin($pluginAccessName);

		if ($plugin['activateMoment'] == 'whenCallAlways' || !$this->isPluginActivated($pluginAccessName))
			return $this->activatePlugin($pluginAccessName);
		else
			return $this->activatedPlugins[$pluginAccessName];
	}

	protected function isPluginActivated($pluginAccessName)
	{
		return array_key_exists($pluginAccessName, $this->activatedPlugins);
	}
	
	protected function activatePlugin($pluginAccessName)
	{
		$manager = Config::getPluginsManagerClass();
		$this->activatedPlugins[$pluginAccessName] = $manager::createPluginInstance($this, $pluginAccessName);
		return $this->activatedPlugins[$pluginAccessName];
	}

	protected function dispatchEvent($eventName)
	{
		$args = func_get_args();
		unset($args[0]);
		$args = array_values($args);

		$manager = Config::getPluginsManagerClass();
		foreach ($manager::getAccessNamesForEventPlugins($eventName) as $pluginAccessName)
		{
			$pluginInstance = $this->callPlugin($pluginAccessName);
			call_user_func_array(array($pluginInstance, $eventName), $args);
		}
	}

/**/

	public function setName($name)
	{
		$this->handleSpecModifyDeny();
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

/**/

	public function setParent(SpecContainerInterface $spec = null)
	{
		$this->handleSpecModifyDeny();
		$this->parent = $spec;
	}

	/**
	 * @return SpecContainerInterface
	 */
	public function getParent()
	{
		return $this->parent;
	}

	public function removeFromParent()
	{
		$this->handleSpecModifyDeny();
		if ($this->getParent())
		{
			$this->getParent()->removeSpec($this);
			$this->setParent(null);
		}
	}

/**/

	public function enable()
	{
		$this->handleSpecModifyDeny();
		$this->isEnabled = true;
		$this->isEnabledTemporarily = null;
	}

	public function disable()
	{
		$this->handleSpecModifyDeny();
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

	protected function enableTemporarily()
	{
		$this->isEnabledTemporarily = true;
	}

	protected function disableTemporarily()
	{
		$this->isEnabledTemporarily = false;
	}

	protected function resetEnabledTemporarily()
	{
		$this->isEnabledTemporarily = null;
	}

/**/

	public function isRunning()
	{
		return $this->isRunning;
	}

	protected function startRun()
	{
		$this->isRunning = true;
		$this->messages->clear();
	}

	protected function stopRun()
	{
		$this->isRunning = false;
	}

	protected function runSelfThroughAncestors()
	{
		$this->disableSiblingsTemporarily();
		$this->enableTemporarily();

		$result = $this->getParent()->run();

		$this->resetEnabledTemporarily();
		$this->resetSiblingsEnabledTemporarily();

		return $result;
	}

	protected function disableSiblingsTemporarily()
	{
		foreach ($this->getParent()->getSpecs() as $spec)
		{
			if ($this->isSibling($spec))
				$spec->disableTemporarily();
		}
	}

	protected function resetSiblingsEnabledTemporarily()
	{
		foreach ($this->getParent()->getSpecs() as $spec)
		{
			if ($this->isSibling($spec))
				$spec->resetEnabledTemporarily();
		}
	}

	// TODO: rename to more exact name
	protected function isSibling(SpecInterface $spec)
	{
		return !($spec instanceof SpecContainerContextInterface);
	}

/**/

	protected function calculateFinalResult(array $results)
	{
		$hasEmpty = false;
		foreach ($results as $result)
		{
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

	protected function handleSpecModifyDeny()
	{
		if (!Config::getAllowSpecsModifyWhenRunning() && $this->selector->getRoot()->isRunning())
			throw new Exception('Modify specs when running deny in Config');
	}
}