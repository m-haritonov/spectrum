<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\events;

interface OnTestCallbackCallInterface extends EventInterface
{
	public function onTestCallbackCallBefore(\spectrum\core\WorldInterface $world);
	public function onTestCallbackCallAfter(\spectrum\core\WorldInterface $world);
}