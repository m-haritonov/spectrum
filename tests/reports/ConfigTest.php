<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports;
require_once dirname(__FILE__) . '/../init.php';

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