<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\testHelpers\emptyStubs\core\plugins\events;

use spectrum\core\plugins\events\OnTestCallbackCallAfterInterface;

class OnTestCallbackCallAfter implements OnTestCallbackCallAfterInterface
{
	public function __construct(\spectrum\core\SpecInterface $ownerSpec, $accessName){}
	public function getOwnerSpec(){}
	public function getAccessName(){}
	public function onTestCallbackCallAfter(){}
}