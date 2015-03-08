<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

interface SpecInterface {
	public function enable();
	public function disable();

	/**
	 * @return bool
	 */
	public function isEnabled();

/**/

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

/**/

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
	
/**/
	
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

/**/
	
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
	 * Return running ancestor specs from parent to root
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

/**/

	/**
	 * @return ContextModifiersInterface
	 */
	public function getContextModifiers();
	
	/**
	 * @return DataInterface
	 */
	public function getData();
	
	/**
	 * @return ErrorHandlingInterface
	 */
	public function getErrorHandling();
	
	/**
	 * @return ExecutorInterface
	 */
	public function getExecutor();
	
	/**
	 * @return MatchersInterface
	 */
	public function getMatchers();
	
	/**
	 * @return MessagesInterface
	 */
	public function getMessages();
	
	/**
	 * @return null|ResultBufferInterface
	 */
	public function getResultBuffer();

/**/

	/*
	 * format: <ancestor spec index in parent>x<next ancestor spec index in parent>x<etc.>
	 * example: "0x1x24"
	 * 
	 * @return string String in "US-ASCII" charset
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