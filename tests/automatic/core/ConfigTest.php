<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core;

use spectrum\core\config;
use spectrum\tests\automatic\Test;

require_once __DIR__ . '/../../init.php';

class ConfigTest extends Test {
	public function testSetInputCharset_SetsNewValue() {
		config::setInputCharset('windows-1251');
		$this->assertSame('windows-1251', config::getInputCharset());
		
		config::setInputCharset('utf-8');
		$this->assertSame('utf-8', config::getInputCharset());
	}

	public function testSetInputCharset_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue() {
		config::setInputCharset('utf-8');
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', '\spectrum\core\config is locked', function(){
			config::setInputCharset('windows-1251');
		});

		$this->assertSame('utf-8', config::getInputCharset());
	}
		
/**/
	
	public function testGetInputCharset_ReturnsUtf8ByDefault() {
		$this->assertSame('utf-8', config::getInputCharset());
	}
	
	public function testGetInputCharset_ConfigIsLocked_DoesNotThrowException() {
		config::lock();
		config::getInputCharset();
	}

/**/

	public function testSetOutputCharset_SetsNewValue() {
		config::setOutputCharset('windows-1251');
		$this->assertSame('windows-1251', config::getOutputCharset());
		
		config::setOutputCharset('utf-8');
		$this->assertSame('utf-8', config::getOutputCharset());
	}

	public function testSetOutputCharset_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue() {
		config::setOutputCharset('utf-8');
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', '\spectrum\core\config is locked', function(){
			config::setOutputCharset('windows-1251');
		});

		$this->assertSame('utf-8', config::getOutputCharset());
	}
	
/**/

	public function testGetOutputCharset_ReturnsUtf8ByDefault() {
		$this->assertSame('utf-8', config::getOutputCharset());
	}
	
	public function testGetOutputCharset_ConfigIsLocked_DoesNotThrowException() {
		config::lock();
		config::getOutputCharset();
	}
	
/**/

	public function testSetOutputFormat_SetsNewValue() {
		config::setOutputFormat('text');
		$this->assertSame('text', config::getOutputFormat());
		
		config::setOutputFormat('html');
		$this->assertSame('html', config::getOutputFormat());
	}
	
	public function testSetOutputFormat_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue() {
		config::setOutputFormat('html');
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', '\spectrum\core\config is locked', function(){
			config::setOutputFormat('text');
		});

		$this->assertSame('html', config::getOutputFormat());
	}
	
/**/
	
	public function testGetOutputFormat_ReturnsHtmlByDefault() {
		$this->assertSame('html', config::getOutputFormat());
	}
	
	public function testGetOutputFormat_ConfigIsLocked_DoesNotThrowException() {
		config::lock();
		config::getOutputFormat();
	}

/**/

	public function testSetOutputIndention_SetsNewValue() {
		config::setOutputIndention('    ');
		$this->assertSame('    ', config::getOutputIndention());
		
		config::setOutputIndention("\t");
		$this->assertSame("\t", config::getOutputIndention());
		
		config::setOutputIndention(" \t ");
		$this->assertSame(" \t ", config::getOutputIndention());
	}
	
	public function testSetOutputIndention_IncorrectCharIsPassed_ThrowsExceptionAndDoesNotChangeValue() {
		config::setOutputIndention("\t");

		$this->assertThrowsException('\spectrum\core\Exception', 'Incorrect char is passed to "\spectrum\core\config::setOutputIndention" method (only "\t" and " " chars are allowed)', function(){
			config::setOutputIndention('z');
		});

		$this->assertSame("\t", config::getOutputIndention());
	}

	public function testSetOutputIndention_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue() {
		config::setOutputIndention("\t");
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', '\spectrum\core\config is locked', function(){
			config::setOutputIndention('    ');
		});

		$this->assertSame("\t", config::getOutputIndention());
	}

/**/
	
	public function testGetOutputIndention_ReturnsTabByDefault() {
		$this->assertSame("\t", config::getOutputIndention());
	}
	
	public function testGetOutputIndention_ConfigIsLocked_DoesNotThrowException() {
		config::lock();
		config::getOutputIndention();
	}

/**/

	public function testSetOutputNewline_SetsNewValue() {
		config::setOutputNewline("\n");
		$this->assertSame("\n", config::getOutputNewline());
		
		config::setOutputNewline("\r\n\r\n");
		$this->assertSame("\r\n\r\n", config::getOutputNewline());
		
		config::setOutputNewline("\r\n");
		$this->assertSame("\r\n", config::getOutputNewline());
	}
	
	public function testSetOutputNewline_IncorrectCharIsPassed_ThrowsExceptionAndDoesNotChangeValue() {
		config::setOutputNewline("\n");

		$this->assertThrowsException('\spectrum\core\Exception', 'Incorrect char is passed to "\spectrum\core\config::setOutputNewline" method (only "\r" and "\n" chars are allowed)', function(){
			config::setOutputNewline('z');
		});

		$this->assertSame("\n", config::getOutputNewline());
	}

	public function testSetOutputNewline_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue() {
		config::setOutputNewline("\r\n");
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', '\spectrum\core\config is locked', function(){
			config::setOutputNewline("\n");
		});

		$this->assertSame("\r\n", config::getOutputNewline());
	}

/**/

	public function testGetOutputNewline_ReturnsLfByDefault() {
		$this->assertSame("\n", config::getOutputNewline());
	}
	
	public function testGetOutputNewline_ConfigIsLocked_DoesNotThrowException() {
		config::lock();
		config::getOutputNewline();
	}
	
/**/
	
	public function testSetOutputResults_SetsNewValue() {
		config::setOutputResults('all fail success empty unknown');
		$this->assertSame('all fail success empty unknown', config::getOutputResults());
		
		config::setOutputResults('empty unknown');
		$this->assertSame('empty unknown', config::getOutputResults());
		
		config::setOutputResults('all');
		$this->assertSame('all', config::getOutputResults());
	}
	
	public function testSetOutputResults_IncorrectValueIsPassed_ThrowsExceptionAndDoesNotChangeValue() {
		config::setOutputResults('all');

		$this->assertThrowsException('\spectrum\core\Exception', 'Incorrect value is passed to "\spectrum\core\config::setOutputResults" method (only combination of "all", "fail", "success", "empty", "unknown" strings are allowed)', function(){
			config::setOutputResults('z');
		});

		$this->assertSame('all', config::getOutputResults());
	}

	public function testSetOutputResults_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue() {
		config::setOutputResults('all');
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', '\spectrum\core\config is locked', function(){
			config::setOutputResults('fail');
		});

		$this->assertSame('all', config::getOutputResults());
	}

/**/

	public function testGetOutputResults_ReturnsFailEmptyUnknownByDefault() {
		$this->assertSame('fail empty unknown', config::getOutputResults());
	}
	
	public function testGetOutputResults_ConfigIsLocked_DoesNotThrowException() {
		config::lock();
		config::getOutputResults();
	}
	
/**/

	public function testHasOutputResults_CheckedValueIsPresentInSetValue_ReturnsTrue() {
		config::setOutputResults('fail');
		$this->assertSame(true, config::hasOutputResults('fail'));
		
		config::setOutputResults('all fail');
		$this->assertSame(true, config::hasOutputResults('fail'));
		
		config::setOutputResults('fail all');
		$this->assertSame(true, config::hasOutputResults('fail'));
		
		config::setOutputResults('all fail all');
		$this->assertSame(true, config::hasOutputResults('fail'));
		
		config::setOutputResults('empty');
		$this->assertSame(true, config::hasOutputResults('empty'));
	}
	
	public function testHasOutputResults_CheckedValueIsNotPresentInSetValue_ReturnsFalse() {
		config::setOutputResults('fail');
		$this->assertSame(false, config::hasOutputResults('empty'));
	}
	
	public function testSetOutputResults_IncorrectValueIsPassed_ThrowsException() {
		$this->assertThrowsException('\spectrum\core\Exception', 'Incorrect value is passed to "\spectrum\core\config::hasOutputResults" method (only combination of "all", "fail", "success", "empty", "unknown" strings are allowed)', function(){
			config::hasOutputResults('z');
		});
	}
	
	public function testHasOutputResults_ConfigIsLocked_DoesNotThrowException() {
		config::lock();
		config::hasOutputResults('all');
	}
	
/**/

	public function testSetAllowErrorHandlingModify_SetsNewValue() {
		config::setAllowErrorHandlingModify(false);
		$this->assertFalse(config::getAllowErrorHandlingModify());
	}

	public function testSetAllowErrorHandlingModify_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue() {
		config::setAllowErrorHandlingModify(true);
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', '\spectrum\core\config is locked', function(){
			config::setAllowErrorHandlingModify(false);
		});

		$this->assertTrue(config::getAllowErrorHandlingModify());
	}
	
/**/
	
	public function testGetAllowErrorHandlingModify_ReturnsTrueByDefault() {
		$this->assertTrue(config::getAllowErrorHandlingModify());
	}
	
	public function testGetAllowErrorHandlingModify_ConfigIsLocked_DoesNotThrowException() {
		config::lock();
		config::getAllowErrorHandlingModify();
	}
	
/**/

	public function testSetClassReplacement_ClassHasInterface_NewClassImplementsInterface_SetsNewClass() {
		$className = \spectrum\tests\_testware\tools::createClass('
			class ... implements \spectrum\core\AssertionInterface {
				public function __construct(\spectrum\core\SpecInterface $ownerSpec, $testedValue){}
				public function __call($name, array $matcherArguments = array()){}
				public function __get($name){}
			}
		');
		
		config::setClassReplacement('\spectrum\core\Assertion', $className);
		$this->assertSame($className, config::getClassReplacement('\spectrum\core\Assertion'));
	}
	
	public function testSetClassReplacement_ClassHasInterface_NewClassDoesNotImplementInterface_ThrowsExceptionAndDoesNotChangeValue() {
		$className = \spectrum\tests\_testware\tools::createClass('class ... {}');
		$this->assertThrowsException('\spectrum\core\Exception', 'Class "' . $className . '" does not implement "\spectrum\core\AssertionInterface"', function() use($className){
			config::setClassReplacement('\spectrum\core\Assertion', $className);
		});

		$this->assertSame('\spectrum\core\Assertion', config::getClassReplacement('\spectrum\core\Assertion'));
	}

	public function testSetClassReplacement_ClassHasNoInterface_NewClassDoesNotImplementInterface_SetsNewClass() {
		config::setClassReplacement('\spectrum\core\_private\reports\html\driver', '\aaa');
		$this->assertSame('\aaa', config::getClassReplacement('\spectrum\core\_private\reports\html\driver'));
	}
	
	public function testSetClassReplacement_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue() {
		config::setClassReplacement('\spectrum\core\_private\reports\html\driver', '\aaa');
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', '\spectrum\core\config is locked', function(){
			config::setClassReplacement('\spectrum\core\_private\reports\html\driver', '\bbb');
		});

		$this->assertSame('\aaa', config::getClassReplacement('\spectrum\core\_private\reports\html\driver'));
	}
	
/**/
	
	public function testGetClassReplacement_ReturnsSpectrumClassByDefault() {
		$this->assertSame('\spectrum\core\_private\reports\html\driver', config::getClassReplacement('\spectrum\core\_private\reports\html\driver'));
	}
	
	public function testGetClassReplacement_ConfigIsLocked_DoesNotThrowException() {
		config::lock();
		config::getClassReplacement('\spectrum\core\_private\reports\html\driver');
	}
	
/**/

	public function testSetFunctionReplacement_SetsNewClass() {
		config::setFunctionReplacement('\spectrum\core\_private\translate', '\aaa');
		$this->assertSame('\aaa', config::getFunctionReplacement('\spectrum\core\_private\translate'));
	}

	public function testSetFunctionReplacement_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue() {
		config::setFunctionReplacement('\spectrum\core\_private\translate', '\aaa');
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', '\spectrum\core\config is locked', function(){
			config::setFunctionReplacement('\spectrum\core\_private\translate', '\bbb');
		});

		$this->assertSame('\aaa', config::getFunctionReplacement('\spectrum\core\_private\translate'));
	}
	
/**/
	
	public function testGetFunctionReplacement_ReturnsSpectrumClassByDefault() {
		$this->assertSame('\spectrum\core\_private\translate', config::getFunctionReplacement('\spectrum\core\_private\translate'));
	}
	
	public function testGetFunctionReplacement_ConfigIsLocked_DoesNotThrowException() {
		config::lock();
		config::getFunctionReplacement('\spectrum\core\_private\translate');
	}
	
/**/
	
	public function testRegisterEventListener_AddsEventListenerToRegisteredEventListeners() {
		config::unregisterEventListeners();
		
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		config::registerEventListener('onSpecRunStart', $function1, 110);
		$this->assertSame(array(
			array('event' => 'onSpecRunStart', 'callback' => $function1, 'order' => 110),
		), config::getRegisteredEventListeners());
		
		config::registerEventListener('onSpecRunFinish', $function2, 120);
		$this->assertSame(array(
			array('event' => 'onSpecRunStart', 'callback' => $function1, 'order' => 110),
			array('event' => 'onSpecRunFinish', 'callback' => $function2, 'order' => 120),
		), config::getRegisteredEventListeners());
		
		config::registerEventListener('onEndingSpecExecuteBefore', $function3, 130);
		$this->assertSame(array(
			array('event' => 'onSpecRunStart', 'callback' => $function1, 'order' => 110),
			array('event' => 'onSpecRunFinish', 'callback' => $function2, 'order' => 120),
			array('event' => 'onEndingSpecExecuteBefore', 'callback' => $function3, 'order' => 130),
		), config::getRegisteredEventListeners());
	}
	
	public function testRegisterEventListener_OrderEventListeners() {
		config::unregisterEventListeners();
		
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		config::registerEventListener('onSpecRunAfter', $function1, 200);
		config::registerEventListener('onSpecRunFinish', $function2, -100);
		config::registerEventListener('onEndingSpecExecuteBefore', $function3, 100);
		
		$this->assertSame(array(
			array('event' => 'onSpecRunFinish', 'callback' => $function2, 'order' => -100),
			array('event' => 'onEndingSpecExecuteBefore', 'callback' => $function3, 'order' => 100),
			array('event' => 'onSpecRunAfter', 'callback' => $function1, 'order' => 200),
		), config::getRegisteredEventListeners());
	}
	
	public function testRegisterEventListener_ConfigIsLocked_ThrowsExceptionAndDoesNotRegisterEventListener() {
		config::unregisterEventListeners();
		
		$backup = config::getRegisteredEventListeners();
		config::lock();
		
		$this->assertThrowsException('\spectrum\core\Exception', '\spectrum\core\config is locked', function() {
			config::registerEventListener('onSpecRunAfter', function(){}, 100);
		});

		$this->assertSame($backup, config::getRegisteredEventListeners());
	}
	
/**/
	
	public function testUnregisterEventListener_CallbackIsSet_CallbackIsClosure_RemovesEventListenersWithSpecifiedEventAndCallback() {
		config::unregisterEventListeners();
		
		$function1 = function(){};
		$function2 = function(){};
		
		config::registerEventListener('onSpecRunAfter', $function1, 100);
		config::registerEventListener('onSpecRunAfter', $function1, 100);
		config::registerEventListener('onSpecRunFinish', $function2, 200);
		
		config::unregisterEventListener('onSpecRunAfter', $function1);
		
		$this->assertSame(array(
			array('event' => 'onSpecRunFinish', 'callback' => $function2, 'order' => 200),
		), config::getRegisteredEventListeners());
	}
	
	public function testUnregisterEventListener_CallbackIsSet_CallbackIsClosure_DoesNotRemoveEventListenersWithSameEventAndDifferentCallback() {
		config::unregisterEventListeners();
		
		$function1 = function(){};
		$function2 = function(){};
		
		config::registerEventListener('onSpecRunAfter', $function1, 100);
		config::registerEventListener('onSpecRunAfter', $function2, 100);
		
		config::unregisterEventListener('onSpecRunAfter', $function1);
		
		$this->assertSame(array(
			array('event' => 'onSpecRunAfter', 'callback' => $function2, 'order' => 100),
		), config::getRegisteredEventListeners());
	}
	
	public function testUnregisterEventListener_CallbackIsSet_CallbackIsClosure_ResetsEventListenerIndexes() {
		config::unregisterEventListeners();
		
		$function1 = function(){};
		$function2 = function(){};
		
		config::registerEventListener('onSpecRunAfter', $function1, 100);
		config::registerEventListener('onSpecRunFinish', $function2, 200);
		
		config::unregisterEventListener('onSpecRunAfter', $function1);
		
		$this->assertSame(array(
			array('event' => 'onSpecRunFinish', 'callback' => $function2, 'order' => 200),
		), config::getRegisteredEventListeners());
	}
	
	public function providerUnregisterEventListener_CallbackIsSet_CallbackIsString() {
		return array(
			array(
				array(
					array('onSpecRunAfter', 'trim'),
					array('onSpecRunAfter', 'trim'),
				),
				array('onSpecRunAfter', 'trim'),
			),
			
			array(
				array(
					array('onSpecRunAfter', 'trim'),
					array('onSpecRunAfter', 'trim'),
				),
				array('onSpecRunAfter', 'TRIM'),
			),
			
			array(
				array(
					array('onSpecRunAfter', 'TRIM'),
					array('onSpecRunAfter', 'TRIM'),
				),
				array('onSpecRunAfter', 'trim'),
			),
			
			array(
				array(
					array('onSpecRunAfter', 'TRIM'),
					array('onSpecRunAfter', 'TRIM'),
				),
				array('onSpecRunAfter', 'TRIM'),
			),
		);
	}

	/**
	 * @dataProvider providerUnregisterEventListener_CallbackIsSet_CallbackIsString
	 */
	public function testUnregisterEventListener_CallbackIsSet_CallbackIsString_RemovesSpecifiedEventListeners($registeredEventListeners, $expectedEventListener) {
		config::unregisterEventListeners();
		
		foreach ($registeredEventListeners as $registeredEventListener) {
			config::registerEventListener($registeredEventListener[0], $registeredEventListener[1]);
		}
		
		config::unregisterEventListener($expectedEventListener[0], $expectedEventListener[1]);
		
		$this->assertSame(array(), config::getRegisteredEventListeners());
	}
	
	public function testUnregisterEventListener_CallbackIsNotSet_RemovesAllEventListenersWithSpecifiedEvent() {
		config::unregisterEventListeners();
		
		$function1 = function(){};
		$function2 = function(){};
		
		config::registerEventListener('onSpecRunAfter', $function1, 100);
		config::registerEventListener('onSpecRunAfter', $function2, 100);
		config::registerEventListener('onSpecRunFinish', $function2, 200);
		
		config::unregisterEventListener('onSpecRunAfter');
		
		$this->assertSame(array(
			array('event' => 'onSpecRunFinish', 'callback' => $function2, 'order' => 200),
		), config::getRegisteredEventListeners());
	}
	
	public function testUnregisterEventListener_ConfigIsLocked_ThrowsExceptionAndDoesNotUnregisterEventListeners() {
		config::unregisterEventListeners();
		
		$function = function() {};
		
		config::registerEventListener('onSpecRunAfter', $function);
		$backup = config::getRegisteredEventListeners();
		
		config::lock();
		$this->assertThrowsException('\spectrum\core\Exception', '\spectrum\core\config is locked', function() use($function){
			config::unregisterEventListener('onSpecRunAfter', $function);
		});

		$this->assertSame($backup, config::getRegisteredEventListeners());
	}
	
/**/
	
	public function testUnregisterEventListeners_RemovesAllEventListeners() {
		config::unregisterEventListeners();
		
		config::registerEventListener('onSpecRunAfter', function(){}, 100);
		config::registerEventListener('onSpecRunFinish', function(){}, 200);
		
		config::unregisterEventListeners();
		
		$this->assertSame(array(), config::getRegisteredEventListeners());
	}
	
	public function testUnregisterEventListeners_ConfigIsLocked_ThrowsExceptionAndDoesNotUnregisterEventListeners() {
		config::unregisterEventListeners();
		
		config::registerEventListener('onSpecRunAfter', function() {});
		$backup = config::getRegisteredEventListeners();
		
		config::lock();
		$this->assertThrowsException('\spectrum\core\Exception', '\spectrum\core\config is locked', function(){
			config::unregisterEventListeners();
		});

		$this->assertSame($backup, config::getRegisteredEventListeners());
	}
	
/**/
	
	public function testGetRegisteredEventListeners_ReturnsRegisteredEventListeners() {
		config::unregisterEventListeners();
		
		$function = function(){};
		
		config::registerEventListener('onSpecRunAfter', $function, 100);
		config::registerEventListener('onSpecRunFinish', 'trim', 200);
		
		$this->assertSame(array(
			array('event' => 'onSpecRunAfter', 'callback' => $function, 'order' => 100),
			array('event' => 'onSpecRunFinish', 'callback' => 'trim', 'order' => 200),
		), config::getRegisteredEventListeners());
	}
	
	public function testGetRegisteredEventListeners_ConfigIsLocked_DoesNotThrowException() {
		config::lock();
		config::getRegisteredEventListeners();
	}

/**/
	
	public function testHasRegisteredEventListener_CallbackIsSet_CallbackIsClosure_SoughtEventListenerIsRegistered_ReturnsTrue() {
		config::unregisterEventListeners();
		$function = function(){};
		config::registerEventListener('onSpecRunAfter', $function, 100);
		$this->assertSame(true, config::hasRegisteredEventListener('onSpecRunAfter', $function));
	}
	
	public function testHasRegisteredEventListener_CallbackIsSet_CallbackIsClosure_SoughtEventListenerIsNotRegistered_ReturnsFalse() {
		config::unregisterEventListeners();
		$this->assertSame(false, config::hasRegisteredEventListener('onSpecRunAfter', function(){}));
	}
	
	public function providerHasRegisteredEventListener_CallbackIsSet_CallbackIsString_SoughtEventListenerIsRegistered() {
		return array(
			array(
				array(
					array('onSpecRunAfter', 'trim'),
					array('onSpecRunAfter', 'trim'),
				),
				array('onSpecRunAfter', 'trim'),
			),
			
			array(
				array(
					array('onSpecRunAfter', 'trim'),
					array('onSpecRunAfter', 'trim'),
				),
				array('onSpecRunAfter', 'TRIM'),
			),
			
			array(
				array(
					array('onSpecRunAfter', 'TRIM'),
					array('onSpecRunAfter', 'TRIM'),
				),
				array('onSpecRunAfter', 'trim'),
			),
			
			array(
				array(
					array('onSpecRunAfter', 'TRIM'),
					array('onSpecRunAfter', 'TRIM'),
				),
				array('onSpecRunAfter', 'TRIM'),
			),
		);
	}

	/**
	 * @dataProvider providerHasRegisteredEventListener_CallbackIsSet_CallbackIsString_SoughtEventListenerIsRegistered
	 */
	public function testHasRegisteredEventListener_CallbackIsSet_CallbackIsString_SoughtEventListenerIsRegistered_ReturnsTrue($registeredEventListeners, $expectedEventListener) {
		config::unregisterEventListeners();
		
		foreach ($registeredEventListeners as $registeredEventListener) {
			config::registerEventListener($registeredEventListener[0], $registeredEventListener[1]);
		}
		
		$this->assertSame(true, config::hasRegisteredEventListener($expectedEventListener[0], $expectedEventListener[1]));
	}
	
	public function testHasRegisteredEventListener_CallbackIsSet_CallbackIsString_SoughtEventListenerIsNotRegistered_ReturnsFalse() {
		config::unregisterEventListeners();
		$this->assertSame(false, config::hasRegisteredEventListener('onSpecRunAfter', 'trim'));
	}
	
	public function testHasRegisteredEventListener_CallbackIsNotSet_SoughtEventListenerIsRegistered_ReturnsTrue() {
		config::unregisterEventListeners();
		config::registerEventListener('onSpecRunAfter', function() {}, 100);
		$this->assertSame(true, config::hasRegisteredEventListener('onSpecRunAfter'));
	}
	
	public function testHasRegisteredEventListener_CallbackIsNotSet_SoughtEventListenerIsNotRegistered_ReturnsFalse() {
		config::unregisterEventListeners();
		$this->assertSame(false, config::hasRegisteredEventListener('onSpecRunAfter'));
	}
	
	public function testHasRegisteredEventListener_ConfigIsLocked_DoesNotThrowException() {
		config::unregisterEventListeners();
		config::lock();
		config::hasRegisteredEventListener('onSpecRunAfter');
	}
	
/**/
	
	public function testIsLocked_ConfigIsNotLocked_ReturnsFalse() {
		$this->assertSame(false, config::isLocked());
	}
	
	public function testIsLocked_ConfigIsLocked_ReturnsTrue() {
		config::lock();
		$this->assertSame(true, config::isLocked());
	}
}