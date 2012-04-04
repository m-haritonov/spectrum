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

namespace spectrum\constructionCommands\testEnv\emptyStubs;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Manager implements \spectrum\constructionCommands\ManagerInterface
{
	static public function __callStatic($name, $args = array()){}
	static public function callCommand($name, $args = array()){}

	static public function registerCommand($name, $callback){}
	static public function registerCommands($commands){}
	static public function unregisterCommand($name){}
	static public function unregisterAllCommands(){}
	static public function getRegisteredCommands(){}
	static public function getRegisteredCommandCallback($name){}
	static public function hasRegisteredCommand($name){}
}