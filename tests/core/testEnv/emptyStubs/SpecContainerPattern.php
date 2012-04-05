<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv\emptyStubs;

class SpecContainerPattern extends SpecContainer implements \spectrum\core\SpecContainerPatternInterface
{
	public function setArguments($arguments){}
	public function getArguments(){}
}