<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace spectrum\core\testEnv\emptyStubs;

class SpecItemIt extends SpecItem implements \spectrum\core\SpecItemItInterface
{
	public function getRunResultsBuffer(){}
	public function setTestCallback($callback){}
	public function getTestCallback(){}
	public function setAdditionalArguments(array $args){}
	public function getAdditionalArguments(){}
}