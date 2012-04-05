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

namespace spectrum\constructionCommands\baseCommands;
/**
 * @throws \spectrum\constructionCommands\Exception If called not at running state
 * @param  mixed $actualValue
 * @return \spectrum\core\asserts\Assert
 */
function be($actualValue)
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "be" should be call only at running state');

	$class = \spectrum\core\Config::getAssertClass();
	return new $class($actualValue);
}