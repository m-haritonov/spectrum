<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins;

interface PluginInterface
{
	/**
	 * For example, call parent spec plugin: $ownerSpec->getParent()->$accessName()
	 * @param $ownerSpec
	 * @param string $accessName
	 */
	public function __construct(\spectrum\core\SpecInterface $ownerSpec, $accessName);
	public function getOwnerSpec();
	public function getAccessName();
}