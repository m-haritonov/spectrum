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

namespace net\mkharitonov\spectrum\core\testEnv\emptyStubs;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class RunResultsBuffer implements \net\mkharitonov\spectrum\core\RunResultsBufferInterface
{
	public function __construct(\net\mkharitonov\spectrum\core\SpecInterface $ownerSpec){}
	public function getOwnerSpec(){}
	public function addResult($result, $details = null){}
	public function getResults(){}
	public function calculateFinalResult(){}
}