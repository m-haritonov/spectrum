<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands;

interface ManagerInterface
{
	static public function __callStatic($name, $args = array());
	static public function callCommand($name, $args = array());

	static public function registerCommand($name, $callback);
	static public function registerCommands($commands);
	static public function unregisterCommand($name);
	static public function unregisterAllCommands();
	static public function getRegisteredCommands();
	static public function getRegisteredCommandCallback($name);
	static public function hasRegisteredCommand($name);
}