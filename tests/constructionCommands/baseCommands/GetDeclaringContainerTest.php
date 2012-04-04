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
class GetDeclaringContainerTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeReturnNullByDefault()
	{
		$this->assertNull(Manager::getDeclaringContainer());
	}

	public function testShouldBeReturnDeclaringContainer()
	{
		$describe = new \spectrum\core\SpecContainerDescribe();
		Manager::setDeclaringContainer($describe);
		$this->assertSame($describe, Manager::getDeclaringContainer());
	}
}