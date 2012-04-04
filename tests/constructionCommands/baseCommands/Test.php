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

require_once dirname(__FILE__) . '/../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Test extends \spectrum\Test
{
	protected function setUp()
	{
		parent::setUp();
		\spectrum\RootDescribe::getOnceInstance()->removeAllSpecs();
		\spectrum\RootDescribe::getOnceInstance()->matchers->removeAll();
		\spectrum\RootDescribe::getOnceInstance()->builders->removeAll();
		\spectrum\RootDescribe::getOnceInstance()->destroyers->removeAll();
		\spectrum\RootDescribe::getOnceInstance()->patterns->removeAll();
		Manager::setDeclaringContainer(null);
	}
}