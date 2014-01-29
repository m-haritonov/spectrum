<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\core\plugins\basePlugins\reports;
use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../../init.php';

class ReportsTest extends \spectrum\tests\Test
{
	protected function setUp()
	{
		parent::setUp();
		
		$this->restoreClassStaticProperties('\spectrum\config');
	}
	
	public function testOutputFormatIsNotSupported_ThrowsException()
	{
		config::setOutputFormat('aaa');
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Output format "aaa" is not supported', function() use($spec){
			$spec->run();
		});
	}
}