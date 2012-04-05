<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv\emptyStubs;

class SpecContainer extends Spec implements \spectrum\core\SpecContainerInterface
{
	public function addSpec(\spectrum\core\SpecInterface $spec){}
	public function getSpecs(){}
	public function getSpecsToRun(){}
	public function removeSpec(\spectrum\core\SpecInterface $spec){}
}