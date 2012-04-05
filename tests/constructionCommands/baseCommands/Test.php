<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;
use spectrum\constructionCommands\Manager;

require_once dirname(__FILE__) . '/../../init.php';

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