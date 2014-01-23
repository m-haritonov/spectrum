<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\testHelpers\emptyStubs\core\plugins\events;

use spectrum\core\plugins\events\OnTestCallbackCallBeforeInterface;

class OnTestCallbackCallBefore implements OnTestCallbackCallBeforeInterface
{
	public function __construct(\spectrum\core\SpecInterface $ownerSpec, $accessName){}
	public function getOwnerSpec(){}
	public function getAccessName(){}
	public function onTestCallbackCallBefore(){}
}