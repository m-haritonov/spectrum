<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands;
require_once __DIR__ . '/../init.php';

class ManagerTest extends \spectrum\tests\Test
{
	public function testShouldBeHaveRegisteredcommandsByDefault()
	{
		$this->assertSame(array(
			'addPattern' => '\spectrum\constructionCommands\commands\addPattern',
			'addMatcher' => '\spectrum\constructionCommands\commands\addMatcher',
			'beforeEach' => '\spectrum\constructionCommands\commands\beforeEach',
			'afterEach' => '\spectrum\constructionCommands\commands\afterEach',

			'container' => '\spectrum\constructionCommands\commands\container',
			'describe' => '\spectrum\constructionCommands\commands\describe',
			'context' => '\spectrum\constructionCommands\commands\context',
			'it' => '\spectrum\constructionCommands\commands\it',
			'itLikePattern' => '\spectrum\constructionCommands\commands\itLikePattern',

			'the' => '\spectrum\constructionCommands\commands\the',
			'verify' => '\spectrum\constructionCommands\commands\verify',

			'world' => '\spectrum\constructionCommands\commands\world',
			'fail' => '\spectrum\constructionCommands\commands\fail',
			'message' => '\spectrum\constructionCommands\commands\message',

			'getCurrentContainer' => '\spectrum\constructionCommands\commands\getCurrentContainer',
			'setDeclaringContainer' => '\spectrum\constructionCommands\commands\setDeclaringContainer',
			'getDeclaringContainer' => '\spectrum\constructionCommands\commands\getDeclaringContainer',
			'getCurrentItem' => '\spectrum\constructionCommands\commands\getCurrentItem',

			'setSettings' => '\spectrum\constructionCommands\commands\setSettings',

			'isRunningState' => '\spectrum\constructionCommands\commands\isRunningState',
		), manager::getRegisteredCommands());
	}

	public function testCallStatic_ShouldBeCallRegisteredCommandAndPassArgumentsToCallback()
	{
		manager::unregisterAllCommands();
		manager::registerCommand('foo', function($a, $b) use(&$passedA, &$passedB){
			$passedA = $a;
			$passedB = $b;
		});

		manager::foo('aaa', 'bbb');

		$this->assertEquals('aaa', $passedA);
		$this->assertEquals('bbb', $passedB);
	}

	public function testCallStatic_ShouldBeCallRegisteredCommandAndReturnCallbackResult()
	{
		manager::unregisterAllCommands();
		manager::registerCommand('foo', function(){ return 'bar'; });
		$this->assertEquals('bar', manager::foo());
	}

	public function testCallCommand_ShouldBePassArgumentsToRegisteredCommandCallback()
	{
		manager::unregisterAllCommands();
		manager::registerCommand('foo', function($a, $b) use(&$passedA, &$passedB){
			$passedA = $a;
			$passedB = $b;
		});

		manager::callCommand('foo', array('aaa', 'bbb'));

		$this->assertEquals('aaa', $passedA);
		$this->assertEquals('bbb', $passedB);
	}

	public function testCallCommand_ShouldBeReturnRegisteredCommandResult()
	{
		manager::unregisterAllCommands();
		manager::registerCommand('foo', function(){ return 'bar'; });
		$this->assertEquals('bar', manager::callCommand('foo'));
	}

/**/

	public function testRegisterCommand_ShouldBeCollectCommands()
	{
		manager::unregisterAllCommands();

		$this->assertSame(array(), manager::getRegisteredCommands());

		$function1 = function(){};
		$function2 = function(){};
		$function3 = 'testFunc';

		manager::registerCommand('foo', $function1);
		$this->assertSame(array(
			'foo' => $function1,
		), manager::getRegisteredCommands());

		manager::registerCommand('bar', $function2);
		$this->assertSame(array(
			'foo' => $function1,
			'bar' => $function2,
		), manager::getRegisteredCommands());

		manager::registerCommand('baz', $function3);
		$this->assertSame(array(
			'foo' => $function1,
			'bar' => $function2,
			'baz' => $function3,
		), manager::getRegisteredCommands());
	}

	public function testRegisterCommand_ShouldBeReplaceExistsCommand()
	{
		manager::unregisterAllCommands();
		manager::registerCommand('foo', 'fooFunc');
		manager::registerCommand('foo', 'barFunc');

		$this->assertSame(
			array('foo' => 'barFunc')
			, manager::getRegisteredCommands()
		);
	}

	public function testRegisterCommand_ShouldBeAcceptClosureFunction()
	{
		manager::unregisterAllCommands();
		manager::registerCommand('foo', function(){ return 'bar'; });

		$this->assertEquals('bar', manager::foo());
	}

	public function testRegisterCommand_ShouldBeAcceptCreatedAnonymousFunction()
	{
		manager::unregisterAllCommands();
		manager::registerCommand('foo', create_function('', 'return "bar";'));

		$this->assertEquals('bar', manager::foo());
	}

	public function testRegisterCommand_ShouldBeAcceptUserDefinedFunction()
	{
		manager::unregisterAllCommands();
		manager::registerCommand('foo', __CLASS__ . '::myCommand');

		$this->assertEquals('bar', manager::foo());
	}

	public function testRegisterCommand_ShouldBeAcceptCallbackArray()
	{
		manager::unregisterAllCommands();
		manager::registerCommand('foo', array(__CLASS__, 'myCommand'));

		$this->assertEquals('bar', manager::foo());
	}

	public function testRegisterCommand_ShouldBeThrowExceptionIfCommandNameIsNotValidFunctionName()
	{
		manager::unregisterAllCommands();
		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'Bad name', function(){
			manager::registerCommand('-foo', function(){});
		});
	}

	public function testRegisterCommand_ShouldBeThrowExceptionIfNotAllowConstructionCommandsRegistration()
	{
		manager::unregisterAllCommands();
		config::setAllowConstructionCommandsRegistration(false);
		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'Construction commands registration deny', function(){
			manager::registerCommand('foo', function(){});
		});
	}

	public function testRegisterCommand_ShouldBeThrowExceptionIfCommandExistsAndNotAllowConstructionCommandsOverride()
	{
		manager::unregisterAllCommands();
		config::setAllowConstructionCommandsOverride(false);
		manager::registerCommand('foo', function(){});
		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'Construction commands override deny', function(){
			manager::registerCommand('foo', function(){});
		});
	}

	public function testRegisterCommands_ShouldBeAcceptArrayWithCommandNameAndCallback()
	{
		manager::unregisterAllCommands();

		$function1 = function(){};
		$function2 = function(){};
		$function3 = 'testFunc';

		manager::registerCommands(array(
			'foo' => $function1,
			'bar' => $function2,
			'baz' => $function3,
		));

		$this->assertSame(array(
			'foo' => $function1,
			'bar' => $function2,
			'baz' => $function3,
		), manager::getRegisteredCommands());
	}

/**/

	public function testUnregisterCommand_ShouldBeRemoveCommandByName()
	{
		manager::unregisterAllCommands();

		manager::registerCommand('foo', function(){});
		manager::unregisterCommand('foo');

		$this->assertFalse(manager::hasRegisteredCommand('foo'));
		$this->assertSame(array(), manager::getRegisteredCommands());
	}

	public function testUnregisterCommand_ShouldBeThrowExceptionIfNotAllowConstructionCommandsOverride()
	{
		manager::unregisterAllCommands();
		config::setAllowConstructionCommandsOverride(false);
		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'Construction commands override deny', function(){
			manager::unregisterCommand('foo');
		});
	}

/**/

	public function testUnregisterAllCommands_ShouldBeLeaveEmptyArray()
	{
		manager::registerCommand('foo', function(){});
		manager::unregisterAllCommands();
		$this->assertSame(array(), manager::getRegisteredCommands());
	}

	public function testUnregisterAllCommands_ShouldBeThrowExceptionIfNotAllowConstructionCommandsOverride()
	{
		manager::unregisterAllCommands();
		config::setAllowConstructionCommandsOverride(false);
		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'Construction commands override deny', function(){
			manager::unregisterAllCommands('foo');
		});
	}

/**/

	public function testHasRegisteredCommand_ShouldBeReturnTrueIfCommandExists()
	{
		manager::unregisterAllCommands();
		manager::registerCommand('foo', function(){});
		$this->assertTrue(manager::hasRegisteredCommand('foo'));
	}

	public function testHasRegisteredCommand_ShouldBeReturnFalseIfCommandNotExists()
	{
		manager::unregisterAllCommands();
		$this->assertFalse(manager::hasRegisteredCommand('foo'));
	}

	public function testGetRegisteredCommandCallback_ShouldBeReturnCallbackByCommandName()
	{
		manager::unregisterAllCommands();
		$function = function(){};
		manager::registerCommand('foo', $function);

		$this->assertSame($function, manager::getRegisteredCommandCallback('foo'));
	}

	public function testGetRegisteredCommandCallback_ShouldBeThrowExceptionIfCommandNotExists()
	{
		manager::unregisterAllCommands();

		$this->assertThrowException('\spectrum\constructionCommands\Exception', function(){
			manager::getRegisteredCommandCallback('foo');
		});
	}

/**/

	public function myCommand()
	{
		return 'bar';
	}
}