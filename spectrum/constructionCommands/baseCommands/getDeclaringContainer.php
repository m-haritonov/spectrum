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
use spectrum\constructionCommands\Manager;
/**
 * Available at declaring and running state.
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @return \spectrum\core\SpecContainerInterface|null
 */
function getDeclaringContainer()
{
	$reflection = new \ReflectionFunction('\spectrum\constructionCommands\baseCommands\setDeclaringContainer');
	$vars = $reflection->getStaticVariables();
	return $vars['container'];
}