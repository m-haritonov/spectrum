<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\builders\internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class SetSettingsToSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_SettingValueIsString_SetsValueToSpecAsInputCharset()
	{
		$spec = new Spec();
		
		\spectrum\builders\internal\setSettingsToSpec($spec, 'windows-1251');
		$this->assertSame('windows-1251', $spec->charset->getInputCharset());
		
		\spectrum\builders\internal\setSettingsToSpec($spec, 'koi8-r');
		$this->assertSame('koi8-r', $spec->charset->getInputCharset());
	}
	
	public function testCallsAtBuildingState_SettingValueIsInteger_SetsValueToSpecAsCatchPhpErrors()
	{
		$spec = new Spec();
		
		\spectrum\builders\internal\setSettingsToSpec($spec, 1);
		$this->assertSame(1, $spec->errorHandling->getCatchPhpErrors());
		
		\spectrum\builders\internal\setSettingsToSpec($spec, 2);
		$this->assertSame(2, $spec->errorHandling->getCatchPhpErrors());
		
		\spectrum\builders\internal\setSettingsToSpec($spec, 0);
		$this->assertSame(0, $spec->errorHandling->getCatchPhpErrors());
		
		\spectrum\builders\internal\setSettingsToSpec($spec, E_NOTICE);
		$this->assertSame(E_NOTICE, $spec->errorHandling->getCatchPhpErrors());
		
		\spectrum\builders\internal\setSettingsToSpec($spec, E_ERROR);
		$this->assertSame(E_ERROR, $spec->errorHandling->getCatchPhpErrors());
	}
	
	public function testCallsAtBuildingState_SettingValueIsBoolean_SetsValueToSpecAsCatchPhpErrors()
	{
		$spec = new Spec();
		
		\spectrum\builders\internal\setSettingsToSpec($spec, true);
		$this->assertSame(-1, $spec->errorHandling->getCatchPhpErrors());
		
		\spectrum\builders\internal\setSettingsToSpec($spec, false);
		$this->assertSame(0, $spec->errorHandling->getCatchPhpErrors());
		
		\spectrum\builders\internal\setSettingsToSpec($spec, true);
		$this->assertSame(-1, $spec->errorHandling->getCatchPhpErrors());
	}
	
	public function testCallsAtBuildingState_SettingValueIsArray_SetsArrayValuesToSpecAsProperSettings()
	{
		$spec = new Spec();
		
		\spectrum\builders\internal\setSettingsToSpec($spec, array(
			'catchPhpErrors' => E_NOTICE,
			'breakOnFirstPhpError' => true,
			'breakOnFirstMatcherFail' => true,
			'inputCharset' => 'windows-1251',
		));
		
		$this->assertSame(E_NOTICE, $spec->errorHandling->getCatchPhpErrors());
		$this->assertSame(true, $spec->errorHandling->getBreakOnFirstPhpError());
		$this->assertSame(true, $spec->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertSame('windows-1251', $spec->charset->getInputCharset());
		
		\spectrum\builders\internal\setSettingsToSpec($spec, array(
			'inputCharset' => 'koi8-r',
			'breakOnFirstMatcherFail' => false,
			'breakOnFirstPhpError' => false,
			'catchPhpErrors' => E_ERROR,
		));
		
		$this->assertSame(E_ERROR, $spec->errorHandling->getCatchPhpErrors());
		$this->assertSame(false, $spec->errorHandling->getBreakOnFirstPhpError());
		$this->assertSame(false, $spec->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertSame('koi8-r', $spec->charset->getInputCharset());
	}
	
	public function testCallsAtBuildingState_SettingValueIsArrayWithNotSupportedSetting_ThrowsException()
	{
		$spec = new Spec();
		$spec->setName('aaa');
		$this->assertThrowsException('\spectrum\builders\Exception', 'Invalid setting "someSettingName" in spec with name "aaa"', function() use($spec){
			\spectrum\builders\internal\setSettingsToSpec($spec, array('someSettingName' => 'windows-1251'));
		});
	}
	
	public function testCallsAtBuildingState_SettingValueHasNotSupportedType_ThrowsException()
	{
		$spec = new Spec();
		$spec->setName('aaa');
		$this->assertThrowsException('\spectrum\builders\Exception', 'Invalid settings variable type ("object") in spec with name "aaa"', function() use($spec){
			\spectrum\builders\internal\setSettingsToSpec($spec, new \stdClass());
		});
	}
}