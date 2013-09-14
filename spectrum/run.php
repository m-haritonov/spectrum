<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum;

function run()
{
	$callBrokerClass = config::getConstructionCommandsCallBrokerClass();
	return $callBrokerClass::getRootSpec()->run();
}