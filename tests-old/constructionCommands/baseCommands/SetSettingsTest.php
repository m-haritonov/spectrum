<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands;
use spectrum\constructionCommands\manager;

require_once __DIR__ . '/../../init.php';

class SetSettingsTest extends \spectrum\constructionCommands\commands\Test
{
	private $spec;
	protected function setUp()
	{
		parent::setUp();
		$this->spec = new \spectrum\core\SpecContainerDescribe();
	}
	
	public function testShouldBeAcceptStringAsSetInputEncoding()
	{
		$this->assertNotSame('windows-1251', $this->spec->output->getInputEncoding());
		manager::setSettings($this->spec, 'windows-1251');
		$this->assertSame('windows-1251', $this->spec->output->getInputEncoding());
	}

	public function testShouldBeAcceptIntegerAsSetCatchPhpErrors()
	{
		$this->assertNotSame(2, $this->spec->errorHandling->getCatchPhpErrors());
		manager::setSettings($this->spec, 2);
		$this->assertSame(2, $this->spec->errorHandling->getCatchPhpErrors());
	}

	public function testShouldBeAcceptTrueAsSetCatchPhpErrors()
	{
		$this->assertNotSame(-1, $this->spec->errorHandling->getCatchPhpErrors());
		manager::setSettings($this->spec, true);
		$this->assertSame(-1, $this->spec->errorHandling->getCatchPhpErrors());
	}

	public function testShouldBeAcceptArrayWithAllSettings()
	{
		$this->assertNotSame(2, $this->spec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(true, $this->spec->errorHandling->getBreakOnFirstPhpError());
		$this->assertNotSame(true, $this->spec->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertNotSame('windows-1251', $this->spec->output->getInputEncoding());

		manager::setSettings($this->spec, array(
			'catchExceptions' => false,
			'catchPhpErrors' => 2,
			'breakOnFirstPhpError' => true,
			'breakOnFirstMatcherFail' => true,
			'inputEncoding' => 'windows-1251',
		));

		$this->assertSame(2, $this->spec->errorHandling->getCatchPhpErrors());
		$this->assertSame(true, $this->spec->errorHandling->getBreakOnFirstPhpError());
		$this->assertSame(true, $this->spec->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertSame('windows-1251', $this->spec->output->getInputEncoding());
	}

	public function testShouldBeThrowExceptionIfSettingsArrayContainsUnknownKey()
	{
		$spec = $this->spec;
		$this->assertThrowException('\spectrum\constructionCommands\Exception',
			'Invalid setting "fooBarBaz"', function() use($spec){
			manager::setSettings($spec, array('fooBarBaz' => true));
		});
	}

	public function testShouldBeThrowExceptionIfSettingsHasUnsupportedType()
	{
		$spec = $this->spec;
		$this->assertThrowException('\spectrum\constructionCommands\Exception',
			'Invalid $settings type ("object")', function() use($spec){
			manager::setSettings($spec, new \stdClass());
		});
	}
}