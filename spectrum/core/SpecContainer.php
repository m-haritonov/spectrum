<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

abstract class SpecContainer extends Spec implements SpecContainerInterface
{
	protected $runningSpecContainerBackup;

	/** @var Spec[] */
	protected $specs = array();

	public function __clone()
	{
		foreach ($this->specs as $key => $spec)
		{
			$clone = clone $spec;
			$clone->setParent($this);

			$this->specs[$key] = $clone;
		}
	}

	public function isAnonymous()
	{
		return ($this->getName() == '');
	}

	public function addSpec(SpecInterface $spec)
	{
		$this->handleSpecModifyDeny();
		$this->specs[] = $spec;
		$spec->setParent($this);
	}

	public function getSpecs()
	{
		return $this->specs;
	}

	public function removeSpec(SpecInterface $spec)
	{
		$this->handleSpecModifyDeny();
		foreach ($this->specs as $key => $val)
		{
			if ($val === $spec)
				unset($this->specs[$key]);
		}
	}

	public function removeAllSpecs()
	{
		$this->handleSpecModifyDeny();
		$this->specs = array();
	}

	public function run()
	{
		if ($this->getParent() && !$this->getParent()->isRunning())
			return $this->runSelfThroughAncestors();

		$this->startRun();
		$this->dispatchEvent('onRunBefore');
		$this->dispatchEvent('onRunContainerBefore');

		$results = array();
		foreach ($this->getSpecsToRun() as $spec)
		{
			if ($spec->isEnabled())
				$results[] = $spec->run();
		}

		$result = $this->calculateFinalResult($results);

		$this->dispatchEvent('onRunContainerAfter', $result);
		$this->dispatchEvent('onRunAfter', $result);
		$this->stopRun();

		return $result;
	}

	public function getSpecsToRun()
	{
		$childContexts = $this->selector->getChildContexts();

		if (count($childContexts))
			return $childContexts;
		else
			return $this->getSpecs();
	}

	protected function startRun()
	{
		parent::startRun();

		$registryClass = \spectrum\core\Config::getRegistryClass();
		$this->runningSpecContainerBackup = $registryClass::getRunningSpecContainer();
		$registryClass::setRunningSpecContainer($this);
	}

	protected function stopRun()
	{
		$registryClass = \spectrum\core\Config::getRegistryClass();
		$registryClass::setRunningSpecContainer($this->runningSpecContainerBackup);

		parent::stopRun();
	}
}