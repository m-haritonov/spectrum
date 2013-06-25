<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins\basePlugins\reports;
use spectrum\core\config;

require_once __DIR__ . '/../init.php';

class PluginTest extends Test
{
	protected function setUp()
	{
		parent::setUp();
		\spectrum\core\plugins\manager::registerPlugin('reports', '\spectrum\core\plugins\basePlugins\reports\Reports', 'firstAccess');
	}

	protected function tearDown()
	{
		parent::tearDown();
		\spectrum\core\plugins\manager::unregisterPlugin('reports');
	}
	
	public function testSetIndention_ShouldBeThrowExceptionIfNotAllowReportSettingsModify()
	{
		config::setAllowReportsSettingsModify(false);
		$this->assertThrowException('\spectrum\core\plugins\basePlugins\reports\Exception', 'Reports settings modify deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->reports->setIndention(false);
		});
	}

	public function testSetNewline_ShouldBeThrowExceptionIfNotAllowReportSettingsModify()
	{
		config::setAllowReportsSettingsModify(false);
		$this->assertThrowException('\spectrum\core\plugins\basePlugins\reports\Exception', 'Reports settings modify deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->reports->setNewline(false);
		});
	}
}