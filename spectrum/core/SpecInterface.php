<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

interface SpecInterface
{
	public function __get($pluginAccessName);

	public function setName($name);
	public function getName();
	public function isAnonymous();

	public function setParent(SpecContainerInterface $spec = null);
	public function getParent();

	public function getPlugin($pluginAccessName);

	public function enable();
	public function disable();
	public function isEnabled();

	public function run();
	public function isRunning();
}