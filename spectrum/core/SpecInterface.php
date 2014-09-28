<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;
use spectrum\core\plugins\PluginInterface;

/**
 * @property \spectrum\core\plugins\ContextModifiers contextModifiers
 * @property \spectrum\core\plugins\ErrorHandling errorHandling
 * @property \spectrum\core\plugins\reports\Reports reports
 * @property \spectrum\core\plugins\Matchers matchers
 * @property \spectrum\core\plugins\Messages messages
 * @property \spectrum\core\plugins\Test test
 */
interface SpecInterface {
	/**
	 * @param string $pluginAccessName
	 * @return PluginInterface
	 */
	public function __get($pluginAccessName);
	
	public function enable();
	public function disable();

	/**
	 * @return bool
	 */
	public function isEnabled();

	/**
	 * @param string $name
	 */
	public function setName($name);

	/**
	 * @return string
	 */
	public function getName();
	
	/**
	 * @return bool
	 */
	public function isAnonymous();
	
	/**
	 * @return SpecInterface[]
	 */
	public function getParentSpecs();

	/**
	 * @return bool
	 */
	public function hasParentSpec(SpecInterface $spec);
	public function bindParentSpec(SpecInterface $spec);
	public function unbindParentSpec(SpecInterface $spec);
	public function unbindAllParentSpecs();

	/**
	 * @return SpecInterface[]
	 */
	public function getChildSpecs();
	
	/**
	 * @return bool
	 */
	public function hasChildSpec(SpecInterface $spec);
	public function bindChildSpec(SpecInterface $spec);
	public function unbindChildSpec(SpecInterface $spec);
	public function unbindAllChildSpecs();
	
	/**
	 * @return SpecInterface[]
	 */
	public function getAncestorRootSpecs();
	
	/**
	 * @return SpecInterface[]
	 */
	public function getDescendantEndingSpecs();
	
	/**
	 * @return null|SpecInterface
	 */
	public function getRunningParentSpec();
	
	/**
	 * @return SpecInterface[]
	 */
	public function getRunningAncestorSpecs();
	
	/**
	 * @return null|SpecInterface
	 */
	public function getRunningChildSpec();
	
	/**
	 * @return null|SpecInterface
	 */
	public function getRunningDescendantEndingSpec();
	
	/**
	 * @return SpecInterface[]
	 */
	public function getSpecsByRunId($runId);

	/**
	 * @return null|ResultBuffer
	 */
	public function getResultBuffer();

	/**
	 * @return string
	 */
	public function getRunId();
	
	/**
	 * @return bool
	 */
	public function isRunning();
	
	/**
	 * @return null|bool
	 */
	public function run();
}