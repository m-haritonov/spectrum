<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace spectrum\core\plugins;

interface ManagerInterface
{
	static public function registerPlugin($accessName, $class = '\spectrum\core\plugins\basePlugins\stack\Indexed', $activateMoment = 'whenCallOnce');
	static public function registerPlugins($plugins);
	static public function unregisterPlugin($accessName);
	static public function unregisterAllPlugins();
	static public function getRegisteredPlugins();
	static public function getAccessNamesForEventPlugins($eventName);
	static public function getRegisteredPlugin($accessName);
	static public function hasRegisteredPlugin($accessName);
	static public function createPluginInstance(\spectrum\core\SpecInterface $ownerSpec, $accessName);
}