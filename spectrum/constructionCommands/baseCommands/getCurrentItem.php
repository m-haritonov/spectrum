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
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @throws \spectrum\constructionCommands\Exception If called not at running state
 * @return \spectrum\core\SpecItemInterface|null
 */
function getCurrentItem()
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "getCurrentItem" should be call only at running state');

	$registryClass = \spectrum\core\Config::getRegistryClass();
	return $registryClass::getRunningSpecItem();
}