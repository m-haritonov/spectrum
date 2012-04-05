<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\events;

interface OnRunContainerInterface extends EventInterface
{
	public function onRunContainerBefore();
	public function onRunContainerAfter($result);
}