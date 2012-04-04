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

namespace spectrum\reports;
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class PluginTest extends Test
{
	public function testSetIndention_ShouldBeThrowExceptionIfNotAllowReportSettingsModify()
	{
		Config::setAllowPluginSettingsModify(false);
		$this->assertThrowException('\spectrum\reports\Exception', 'Reports settings modify deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->reports->setIndention(false);
		});
	}

	public function testSetNewline_ShouldBeThrowExceptionIfNotAllowReportSettingsModify()
	{
		Config::setAllowPluginSettingsModify(false);
		$this->assertThrowException('\spectrum\reports\Exception', 'Reports settings modify deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->reports->setNewline(false);
		});
	}
}