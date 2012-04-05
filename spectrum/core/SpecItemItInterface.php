<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

interface SpecItemItInterface extends SpecItemInterface
{
	public function setTestCallback($callback);
	public function getTestCallback();
	public function setAdditionalArguments(array $args);
	public function getAdditionalArguments();
}