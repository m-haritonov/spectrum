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
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state
 * @param  string $name
 * @param  callback $callback
 */
function itLikePattern($name /* ,... */)
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isDeclaringState())
		throw new \spectrum\constructionCommands\Exception('Construction command "itLikePattern" should be call only at declaring state');

	$patternClass = \spectrum\core\Config::getSpecContainerPatternClass();
	$spec = new $patternClass();
	$spec->setName($name);

	$args = func_get_args();
	unset($args[0]);
	$args = array_values($args);
	$spec->setArguments($args);

	$managerClass::getCurrentContainer()->addSpec($spec);

	$declaringSpecContainerBackup = $managerClass::getDeclaringContainer();
	$managerClass::setDeclaringContainer($spec);
	// Running contexts not used in this case
	call_user_func_array($spec->patterns->getCascadeThroughRunningContexts($name), $spec->getArguments());
	$managerClass::setDeclaringContainer($declaringSpecContainerBackup);

	return $spec;
}