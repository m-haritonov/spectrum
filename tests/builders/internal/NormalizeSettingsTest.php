<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\builders\internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class NormalizeSettingsTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_SettingValueIsNull_ReturnsArrayWithNullSettings()
	{
		$this->assertSame(array(
			'catchPhpErrors' => null,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
			'inputCharset' => null,
		), \spectrum\builders\internal\normalizeSettings(null));
	}
	
	public function testCallsAtBuildingState_SettingValueIsString_ReturnsArrayWithNullSettingsAndInputCharset()
	{
		$this->assertSame(array(
			'catchPhpErrors' => null,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
			'inputCharset' => 'windows-1251',
		), \spectrum\builders\internal\normalizeSettings('windows-1251'));
		
		$this->assertSame(array(
			'catchPhpErrors' => null,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
			'inputCharset' => 'koi8-r',
		), \spectrum\builders\internal\normalizeSettings('koi8-r'));
	}
	
	public function testCallsAtBuildingState_SettingValueIsInteger_ReturnsArrayWithNullSettingsAndCatchPhpErrors()
	{
		$this->assertSame(array(
			'catchPhpErrors' => 0,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
			'inputCharset' => null,
		), \spectrum\builders\internal\normalizeSettings(0));
		
		$this->assertSame(array(
			'catchPhpErrors' => 1,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
			'inputCharset' => null,
		), \spectrum\builders\internal\normalizeSettings(1));
		
		$this->assertSame(array(
			'catchPhpErrors' => 2,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
			'inputCharset' => null,
		), \spectrum\builders\internal\normalizeSettings(2));
		
		$this->assertSame(array(
			'catchPhpErrors' => E_NOTICE,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
			'inputCharset' => null,
		), \spectrum\builders\internal\normalizeSettings(E_NOTICE));
		
		$this->assertSame(array(
			'catchPhpErrors' => E_ERROR,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
			'inputCharset' => null,
		), \spectrum\builders\internal\normalizeSettings(E_ERROR));
	}
	
	public function testCallsAtBuildingState_SettingValueIsBoolean_ReturnsArrayWithNullSettingsAndCatchPhpErrors()
	{
		$this->assertSame(array(
			'catchPhpErrors' => true,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
			'inputCharset' => null,
		), \spectrum\builders\internal\normalizeSettings(true));
		
		$this->assertSame(array(
			'catchPhpErrors' => false,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
			'inputCharset' => null,
		), \spectrum\builders\internal\normalizeSettings(false));
	}
	
	public function testCallsAtBuildingState_SettingValueIsArray_ReturnsArrayWithProperSettings()
	{
		$this->assertSame(array(
			'catchPhpErrors' => E_NOTICE,
			'breakOnFirstPhpError' => true,
			'breakOnFirstMatcherFail' => true,
			'inputCharset' => 'windows-1251',
		), \spectrum\builders\internal\normalizeSettings(array(
			'catchPhpErrors' => E_NOTICE,
			'breakOnFirstPhpError' => true,
			'breakOnFirstMatcherFail' => true,
			'inputCharset' => 'windows-1251',
		)));
		
		$this->assertSame(array(
			'catchPhpErrors' => E_ERROR,
			'breakOnFirstPhpError' => false,
			'breakOnFirstMatcherFail' => false,
			'inputCharset' => 'koi8-r',
		), \spectrum\builders\internal\normalizeSettings(array(
			'catchPhpErrors' => E_ERROR,
			'breakOnFirstPhpError' => false,
			'breakOnFirstMatcherFail' => false,
			'inputCharset' => 'koi8-r',
		)));
	}
	
	public function testCallsAtBuildingState_SettingValueIsArrayWithNotSupportedSetting_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\builders\Exception', 'Invalid setting "someSettingName"', function(){
			\spectrum\builders\internal\normalizeSettings(array('someSettingName' => 'windows-1251'));
		});
	}
	
	public function testCallsAtBuildingState_SettingValueHasNotSupportedType_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\builders\Exception', 'Invalid settings variable type ("object")', function(){
			\spectrum\builders\internal\normalizeSettings(new \stdClass());
		});
	}
}