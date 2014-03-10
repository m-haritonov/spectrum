<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core;

interface SpecInterface
{
	public function __get($pluginAccessName);
	
	public function enable();
	public function disable();
	public function isEnabled();

	public function setName($name);
	public function getName();
	public function isAnonymous();
	
	public function getParentSpecs();
	public function hasParentSpec(SpecInterface $spec);
	public function bindParentSpec(SpecInterface $spec);
	public function unbindParentSpec(SpecInterface $spec);
	public function unbindAllParentSpecs();

	public function getChildSpecs();
	public function hasChildSpec(SpecInterface $spec);
	public function bindChildSpec(SpecInterface $spec);
	public function unbindChildSpec(SpecInterface $spec);
	public function unbindAllChildSpecs();
	
	public function getRootSpecs();
	public function getEndingSpecs();
	public function getRunningParentSpec();
	public function getRunningAncestorSpecs();
	public function getRunningChildSpec();
	public function getRunningEndingSpec();
	public function getSpecsByRunId($id);

	/**
	 * @return null|ResultBuffer
	 */
	public function getResultBuffer();
	public function getRunId();
	public function isRunning();
	public function run();
}