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
 * @param  callback $callback
 */
function afterEach($callback)
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isDeclaringState())
		throw new \spectrum\constructionCommands\Exception('Construction command "afterEach" should be call only at declaring state');

	return $managerClass::getCurrentContainer()->destroyers->add($callback, 'each');
}