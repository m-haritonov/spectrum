<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\testHelpers\emptyStubs\constructionCommands;

class manager implements \spectrum\constructionCommands\managerInterface
{
	static public function __callStatic($name, $args = array()){}
	static public function callCommand($name, $args = array()){}

	static public function registerConstructionCommand($name, $callback){}
	static public function registerCommands($commands){}
	static public function unregisterConstructionCommand($name){}
	static public function unregisterAllConstructionCommands(){}
	static public function getAllRegisteredConstructionCommands(){}
	static public function getRegisteredConstructionCommandCallback($name){}
	static public function hasRegisteredConstructionCommand($name){}
}