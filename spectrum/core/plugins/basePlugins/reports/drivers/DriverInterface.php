<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers;

use spectrum\core\config;
use spectrum\core\specs\SpecInterface;
use spectrum\core\plugins\basePlugins\reports\Reports;

interface DriverInterface
{
	public function __construct(Reports $ownerPlugin);
	public function getOwnerPlugin();
	public function getContentBeforeSpec();
	public function getContentAfterSpec();
}