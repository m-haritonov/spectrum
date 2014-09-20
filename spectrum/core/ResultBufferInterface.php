<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

interface ResultBufferInterface {
	public function __construct(\spectrum\core\SpecInterface $ownerSpec);
	public function getOwnerSpec();
	
	public function addResult($result, $details = null);
	public function getResults();
	public function getTotalResult();
	
	public function lock();
	public function isLocked();
}