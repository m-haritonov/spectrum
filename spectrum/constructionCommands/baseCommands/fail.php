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
 * Add to RunResultsBuffer of running SpecItem false result wits exception as details.
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @throws \spectrum\constructionCommands\Exception If called not at running state
 * @param string|null $message
 * @param int $code
 */
function fail($message = null, $code = 0)
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "fail" should be call only at running state');
	
	$managerClass::getCurrentItem()->getRunResultsBuffer()->addResult(false, new \spectrum\constructionCommands\ExceptionFail($message, $code));
}