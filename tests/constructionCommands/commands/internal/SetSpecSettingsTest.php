<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands\internal;

use spectrum\constructionCommands\callBroker;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../init.php';

class SetSpecSettingsTest extends \spectrum\tests\Test
{
	public function testCallsAtDeclaringState_SettingValueIsString_SetsValueToSpecAsInputCharset()
	{
		$spec = new Spec();
		
		callBroker::internal_setSpecSettings($spec, 'windows-1251');
		$this->assertSame('windows-1251', $spec->charset->getInputCharset());
		
		callBroker::internal_setSpecSettings($spec, 'koi8-r');
		$this->assertSame('koi8-r', $spec->charset->getInputCharset());
	}
	
	public function testCallsAtDeclaringState_SettingValueIsInteger_SetsValueToSpecAsCatchPhpErrors()
	{
		$spec = new Spec();
		
		callBroker::internal_setSpecSettings($spec, 1);
		$this->assertSame(1, $spec->errorHandling->getCatchPhpErrors());
		
		callBroker::internal_setSpecSettings($spec, 2);
		$this->assertSame(2, $spec->errorHandling->getCatchPhpErrors());
		
		callBroker::internal_setSpecSettings($spec, 0);
		$this->assertSame(0, $spec->errorHandling->getCatchPhpErrors());
		
		callBroker::internal_setSpecSettings($spec, E_NOTICE);
		$this->assertSame(E_NOTICE, $spec->errorHandling->getCatchPhpErrors());
		
		callBroker::internal_setSpecSettings($spec, E_ERROR);
		$this->assertSame(E_ERROR, $spec->errorHandling->getCatchPhpErrors());
	}
	
	public function testCallsAtDeclaringState_SettingValueIsBoolean_SetsValueToSpecAsCatchPhpErrors()
	{
		$spec = new Spec();
		
		callBroker::internal_setSpecSettings($spec, true);
		$this->assertSame(-1, $spec->errorHandling->getCatchPhpErrors());
		
		callBroker::internal_setSpecSettings($spec, false);
		$this->assertSame(0, $spec->errorHandling->getCatchPhpErrors());
		
		callBroker::internal_setSpecSettings($spec, true);
		$this->assertSame(-1, $spec->errorHandling->getCatchPhpErrors());
	}
	
	public function testCallsAtDeclaringState_SettingValueIsArray_SetsArrayValuesToSpecAsProperSettings()
	{
		$spec = new Spec();
		
		callBroker::internal_setSpecSettings($spec, array(
			'catchPhpErrors' => E_NOTICE,
			'breakOnFirstPhpError' => true,
			'breakOnFirstMatcherFail' => true,
			'inputCharset' => 'windows-1251',
		));
		
		$this->assertSame(E_NOTICE, $spec->errorHandling->getCatchPhpErrors());
		$this->assertSame(true, $spec->errorHandling->getBreakOnFirstPhpError());
		$this->assertSame(true, $spec->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertSame('windows-1251', $spec->charset->getInputCharset());
		
		callBroker::internal_setSpecSettings($spec, array(
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
	
	public function testCallsAtDeclaringState_SettingValueIsArrayWithNotSupportedSetting_ThrowsException()
	{
		$spec = new Spec();
		$spec->setName('aaa');
		$this->assertThrowsException('\spectrum\constructionCommands\Exception', 'Invalid setting "someSettingName" in spec with name "aaa"', function() use($spec){
			callBroker::internal_setSpecSettings($spec, array('someSettingName' => 'windows-1251'));
		});
	}
	
	public function testCallsAtDeclaringState_SettingValueHasNotSupportedType_ThrowsException()
	{
		$spec = new Spec();
		$spec->setName('aaa');
		$this->assertThrowsException('\spectrum\constructionCommands\Exception', 'Invalid settings variable type ("object") in spec with name "aaa"', function() use($spec){
			callBroker::internal_setSpecSettings($spec, new \stdClass());
		});
	}
}