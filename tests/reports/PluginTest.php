<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports;
require_once dirname(__FILE__) . '/../init.php';

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