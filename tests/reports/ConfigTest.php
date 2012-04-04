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
class ConfigTest extends Test
{
	public function testGetAllowReportSettingsModify_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowPluginSettingsModify());
	}

/**/

	public function testSetAllowReportSettingsModify_ShouldBeSetNewValue()
	{
		Config::setAllowPluginSettingsModify(false);
		$this->assertFalse(Config::getAllowPluginSettingsModify());
	}

	public function testSetAllowReportSettingsModify_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\spectrum\reports\Exception', 'core\Config is locked', function(){
			Config::setAllowPluginSettingsModify(false);
		});

		$this->assertTrue(Config::getAllowPluginSettingsModify());
	}
}