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
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state
 * @param  string $name
 * @param  callback $callback
 */
function addPattern($name, $callback)
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isDeclaringState())
		throw new \spectrum\constructionCommands\Exception('Construction command "addPattern" should be call only at declaring state');

	return $managerClass::getCurrentContainer()->patterns->add($name, $callback);
}