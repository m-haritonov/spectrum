<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\events;

interface OnRunInterface extends EventInterface
{
	public function onRunBefore();
	public function onRunAfter($result);
}