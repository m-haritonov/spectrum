<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
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