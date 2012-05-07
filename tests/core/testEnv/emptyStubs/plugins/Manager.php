<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv\emptyStubs\plugins;

class Manager implements \spectrum\core\plugins\ManagerInterface
{
	static public function registerPlugin($accessName, $class = '\spectrum\core\plugins\basePlugins\stack\Indexed', $activateMoment = 'firstAccess'){}
	static public function registerPlugins($plugins){}
	static public function unregisterPlugin($accessName){}
	static public function unregisterAllPlugins(){}
	static public function getRegisteredPlugins(){}
	static public function getAccessNamesForEventPlugins($eventName){}
	static public function getRegisteredPlugin($accessName){}
	static public function hasRegisteredPlugin($accessName){}
	static public function createPluginInstance(\spectrum\core\SpecInterface $ownerSpec, $accessName){}
}