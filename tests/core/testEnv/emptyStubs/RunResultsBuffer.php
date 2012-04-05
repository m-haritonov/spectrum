<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv\emptyStubs;

class RunResultsBuffer implements \spectrum\core\RunResultsBufferInterface
{
	public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
	public function getOwnerSpec(){}
	public function addResult($result, $details = null){}
	public function getResults(){}
	public function calculateFinalResult(){}
}