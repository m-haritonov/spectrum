<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_private;

require_once __DIR__ . '/../../init.php';

class NormalizeSettingsTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_SettingValueIsNull_ReturnsArrayWithNullSettings() {
		$this->assertSame(array(
			'catchPhpErrors' => null,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
		), \spectrum\_private\normalizeSettings(null));
	}
	
	public function testCallsAtBuildingState_SettingValueIsInteger_ReturnsArrayWithNullSettingsAndCatchPhpErrors() {
		$this->assertSame(array(
			'catchPhpErrors' => 0,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
		), \spectrum\_private\normalizeSettings(0));
		
		$this->assertSame(array(
			'catchPhpErrors' => 1,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
		), \spectrum\_private\normalizeSettings(1));
		
		$this->assertSame(array(
			'catchPhpErrors' => 2,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
		), \spectrum\_private\normalizeSettings(2));
		
		$this->assertSame(array(
			'catchPhpErrors' => E_NOTICE,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
		), \spectrum\_private\normalizeSettings(E_NOTICE));
		
		$this->assertSame(array(
			'catchPhpErrors' => E_ERROR,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
		), \spectrum\_private\normalizeSettings(E_ERROR));
	}
	
	public function testCallsAtBuildingState_SettingValueIsBoolean_ReturnsArrayWithNullSettingsAndCatchPhpErrors() {
		$this->assertSame(array(
			'catchPhpErrors' => true,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
		), \spectrum\_private\normalizeSettings(true));
		
		$this->assertSame(array(
			'catchPhpErrors' => false,
			'breakOnFirstPhpError' => null,
			'breakOnFirstMatcherFail' => null,
		), \spectrum\_private\normalizeSettings(false));
	}
	
	public function testCallsAtBuildingState_SettingValueIsArray_ReturnsArrayWithProperSettings() {
		$this->assertSame(array(
			'catchPhpErrors' => E_NOTICE,
			'breakOnFirstPhpError' => true,
			'breakOnFirstMatcherFail' => true,
		), \spectrum\_private\normalizeSettings(array(
			'catchPhpErrors' => E_NOTICE,
			'breakOnFirstPhpError' => true,
			'breakOnFirstMatcherFail' => true,
		)));
		
		$this->assertSame(array(
			'catchPhpErrors' => E_ERROR,
			'breakOnFirstPhpError' => false,
			'breakOnFirstMatcherFail' => false,
		), \spectrum\_private\normalizeSettings(array(
			'catchPhpErrors' => E_ERROR,
			'breakOnFirstPhpError' => false,
			'breakOnFirstMatcherFail' => false,
		)));
	}
	
	public function testCallsAtBuildingState_SettingValueIsArrayWithNotSupportedSetting_ThrowsException() {
		$this->assertThrowsException('\spectrum\Exception', 'Invalid setting "someSettingName"', function(){
			\spectrum\_private\normalizeSettings(array('someSettingName' => 'windows-1251'));
		});
	}
	
	public function testCallsAtBuildingState_SettingValueHasNotSupportedType_ThrowsException() {
		$this->assertThrowsException('\spectrum\Exception', 'Invalid settings variable type ("object")', function(){
			\spectrum\_private\normalizeSettings(new \stdClass());
		});
	}
}