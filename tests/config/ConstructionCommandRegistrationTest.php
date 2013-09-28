<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\config;
use spectrum\config;

require_once __DIR__ . '/../init.php';

class ConstructionCommandRegistrationTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::unregisterConstructionCommands();
	}

	public function testHasRegisteredBaseCommandsByDefault()
	{
		$this->restoreStaticProperties('\spectrum\config');
		
		$this->assertSame(array(
			'addMatcher'                                   => '\spectrum\constructionCommands\commands\addMatcher',
			'afterEach'                                    => '\spectrum\constructionCommands\commands\afterEach',
			'be'                                           => '\spectrum\constructionCommands\commands\be',
			'beforeEach'                                   => '\spectrum\constructionCommands\commands\beforeEach',
			'fail'                                         => '\spectrum\constructionCommands\commands\fail',
			'group'                                        => '\spectrum\constructionCommands\commands\group',
			'message'                                      => '\spectrum\constructionCommands\commands\message',
			'test'                                         => '\spectrum\constructionCommands\commands\test',
			'this'                                         => '\spectrum\constructionCommands\commands\this',
			'internal_addExclusionSpec'                    => '\spectrum\constructionCommands\commands\internal\addExclusionSpec',
			'internal_callFunctionOnDeclaringSpec'         => '\spectrum\constructionCommands\commands\internal\callFunctionOnDeclaringSpec',
			'internal_convertContextArrayToSpecs'          => '\spectrum\constructionCommands\commands\internal\convertContextArrayToSpecs',
			'internal_getArgumentsForSpecDeclaringCommand' => '\spectrum\constructionCommands\commands\internal\getArgumentsForSpecDeclaringCommand',
			'internal_getCurrentDeclaringSpec'             => '\spectrum\constructionCommands\commands\internal\getCurrentDeclaringSpec',
			'internal_getCurrentRunningSpec'               => '\spectrum\constructionCommands\commands\internal\getCurrentRunningSpec',
			'internal_getCurrentSpec'                      => '\spectrum\constructionCommands\commands\internal\getCurrentSpec',
			'internal_getRootSpec'                         => '\spectrum\constructionCommands\commands\internal\getRootSpec',
			'internal_filterOutExclusionSpecs'     => '\spectrum\constructionCommands\commands\internal\filterOutExclusionSpecs',
			'internal_getExclusionSpecs'                   => '\spectrum\constructionCommands\commands\internal\getExclusionSpecs',
			'internal_getNameForArguments'                 => '\spectrum\constructionCommands\commands\internal\getNameForArguments',
			'internal_isRunningState'                      => '\spectrum\constructionCommands\commands\internal\isRunningState',
			'internal_loadBaseMatchers'                    => '\spectrum\constructionCommands\commands\internal\loadBaseMatchers',
			'internal_setCurrentDeclaringSpec'             => '\spectrum\constructionCommands\commands\internal\setCurrentDeclaringSpec',
			'internal_setSpecSettings'                     => '\spectrum\constructionCommands\commands\internal\setSpecSettings',
		), config::getRegisteredConstructionCommands());
	}

/**/
	public function testRegisterConstructionCommand_AddsCommandToRegisteredCommands()
	{
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};

		config::registerConstructionCommand('aaa', $function1);
		$this->assertSame(array('aaa' => $function1), config::getRegisteredConstructionCommands());
		
		config::registerConstructionCommand('bbb', $function2);
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2), config::getRegisteredConstructionCommands());
		
		config::registerConstructionCommand('ccc', $function3);
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), config::getRegisteredConstructionCommands());
	}
	
	public function testRegisterConstructionCommand_AcceptsClosureAndString()
	{
		$function = function(){};

		config::registerConstructionCommand('aaa', $function);
		config::registerConstructionCommand('bbb', 'trim');
		$this->assertSame(array('aaa' => $function, 'bbb' => 'trim'), config::getRegisteredConstructionCommands());
	}
	
	public function testRegisterConstructionCommand_ConfigIsLocked_ThrowsExceptionAndDoesNotRegisterCommand()
	{
		$backupOfRegisteredCommands = config::getRegisteredConstructionCommands();
		config::lock();
		
		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::registerConstructionCommand('aaa', function(){});
		});

		$this->assertSame($backupOfRegisteredCommands, config::getRegisteredConstructionCommands());
	}
	
	public function testRegisterConstructionCommand_CommandWithSameNameIsAlreadyRegistered_CommandNameInSameCase_ThrowsExceptionAndDoesNotRegisterCommand()
	{
		config::registerConstructionCommand('aaa', function(){});
		$backupOfRegisteredCommands = config::getRegisteredConstructionCommands();
		
		$this->assertThrowsException('\spectrum\Exception', 'Construction command with name "aaa" is already registered (remove registered construction command before register new)', function(){
			config::registerConstructionCommand('aaa', function(){});
		});

		$this->assertSame($backupOfRegisteredCommands, config::getRegisteredConstructionCommands());
	}
	
	public function testRegisterConstructionCommand_CommandNameHasDenySymbols_ThrowsExceptionAndDoesNotRegisterCommand()
	{
		$backupOfRegisteredCommands = config::getRegisteredConstructionCommands();
		
		$this->assertThrowsException('\spectrum\Exception', 'Construction command name "aaa-bbb" has deny symbols', function(){
			config::registerConstructionCommand('aaa-bbb', function(){});
		});

		$this->assertSame($backupOfRegisteredCommands, config::getRegisteredConstructionCommands());
	}
	
/**/
	
	public function testUnregisterConstructionCommands_NoArguments_RemovesAllRegisteredCommands()
	{
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};

		config::registerConstructionCommand('aaa', $function1);
		config::registerConstructionCommand('bbb', $function2);
		config::registerConstructionCommand('ccc', $function3);
		
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), config::getRegisteredConstructionCommands());
		
		config::unregisterConstructionCommands();
		$this->assertSame(array(), config::getRegisteredConstructionCommands());
	}
	
	public function testUnregisterConstructionCommands_StringWithCommandNameAsFirstArgument_CommandNameInSameCase_RemovesRegisteredCommand()
	{
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};

		config::registerConstructionCommand('aaa', $function1);
		config::registerConstructionCommand('bbb', $function2);
		config::registerConstructionCommand('ccc', $function3);
		
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), config::getRegisteredConstructionCommands());
		
		config::unregisterConstructionCommands('ccc');
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2), config::getRegisteredConstructionCommands());
		
		config::unregisterConstructionCommands('bbb');
		$this->assertSame(array('aaa' => $function1), config::getRegisteredConstructionCommands());
		
		config::unregisterConstructionCommands('aaa');
		$this->assertSame(array(), config::getRegisteredConstructionCommands());
	}
	
	public function testUnregisterConstructionCommands_ArrayWithManyCommandNamesAsFirstArgument_CommandNameInSameCase_RemovesRegisteredCommand()
	{
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};

		config::registerConstructionCommand('aaa', $function1);
		config::registerConstructionCommand('bbb', $function2);
		config::registerConstructionCommand('ccc', $function3);
		
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), config::getRegisteredConstructionCommands());
		
		config::unregisterConstructionCommands(array('aaa', 'ccc'));
		$this->assertSame(array('bbb' => $function2), config::getRegisteredConstructionCommands());
	}
	
	public function testUnregisterConstructionCommands_ConfigIsLocked_ThrowsExceptionAndDoesNotUnregisterCommand()
	{
		config::registerConstructionCommand('aaa', function(){});
		$backupOfRegisteredCommands = config::getRegisteredConstructionCommands();
		
		config::lock();
		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::unregisterConstructionCommands('aaa');
		});

		$this->assertSame($backupOfRegisteredCommands, config::getRegisteredConstructionCommands());
	}
	
/**/
	
	public function testGetRegisteredConstructionCommands_ReturnsEmptyArrayByDefault()
	{
		$this->assertSame(array(), config::getRegisteredConstructionCommands());
	}
	
	public function testGetRegisteredConstructionCommands_ReturnsRegisteredCommands()
	{
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};

		config::registerConstructionCommand('aaa', $function1);
		config::registerConstructionCommand('bbb', $function2);
		config::registerConstructionCommand('ccc', $function3);
		
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), config::getRegisteredConstructionCommands());
	}
	
	public function testGetRegisteredConstructionCommands_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getRegisteredConstructionCommands();
	}

/**/

	public function testGetRegisteredConstructionCommandFunction_CommandNameInSameCase_ReturnsFunctionOfCommand()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		config::registerConstructionCommand('aaa', $function1);
		config::registerConstructionCommand('bbb', $function2);
		
		$this->assertSame($function1, config::getRegisteredConstructionCommandFunction('aaa'));
		$this->assertSame($function2, config::getRegisteredConstructionCommandFunction('bbb'));
	}
	
	public function testGetRegisteredConstructionCommandFunction_ReturnsNullWhenNoMatches()
	{
		config::registerConstructionCommand('aaa', function(){});
		$this->assertSame(null, config::getRegisteredConstructionCommandFunction('zzz'));
	}
	
	public function testGetRegisteredConstructionCommandFunction_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getRegisteredConstructionCommandFunction('zzz');
	}
	
/**/

	public function testHasRegisteredConstructionCommand_SoughtCommandIsRegistered_CommandNameInSameCase_ReturnsTrue()
	{
		config::registerConstructionCommand('aaa', function(){});
		$this->assertSame(true, config::hasRegisteredConstructionCommand('aaa'));
	}
	
	public function testHasRegisteredConstructionCommand_SoughtCommandIsNotRegistered_ReturnsFalse()
	{
		config::registerConstructionCommand('aaa', function(){});
		$this->assertSame(false, config::hasRegisteredConstructionCommand('zzz'));
	}
	
	public function testHasRegisteredConstructionCommand_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::hasRegisteredConstructionCommand('aaa');
	}
}