<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands;
require_once dirname(__FILE__) . '/../init.php';

class ManagerTest extends \spectrum\Test
{
	public function testShouldBeHaveRegisteredBaseCommandsByDefault()
	{
		$this->assertSame(array(
			'addPattern' => '\spectrum\constructionCommands\baseCommands\addPattern',
			'addMatcher' => '\spectrum\constructionCommands\baseCommands\addMatcher',
			'beforeEach' => '\spectrum\constructionCommands\baseCommands\beforeEach',
			'afterEach' => '\spectrum\constructionCommands\baseCommands\afterEach',

			'container' => '\spectrum\constructionCommands\baseCommands\container',
			'describe' => '\spectrum\constructionCommands\baseCommands\describe',
			'context' => '\spectrum\constructionCommands\baseCommands\context',
			'it' => '\spectrum\constructionCommands\baseCommands\it',
			'itLikePattern' => '\spectrum\constructionCommands\baseCommands\itLikePattern',

			'the' => '\spectrum\constructionCommands\baseCommands\the',

			'world' => '\spectrum\constructionCommands\baseCommands\world',
			'fail' => '\spectrum\constructionCommands\baseCommands\fail',
			'message' => '\spectrum\constructionCommands\baseCommands\message',

			'getCurrentContainer' => '\spectrum\constructionCommands\baseCommands\getCurrentContainer',
			'setDeclaringContainer' => '\spectrum\constructionCommands\baseCommands\setDeclaringContainer',
			'getDeclaringContainer' => '\spectrum\constructionCommands\baseCommands\getDeclaringContainer',
			'getCurrentItem' => '\spectrum\constructionCommands\baseCommands\getCurrentItem',

			'setSettings' => '\spectrum\constructionCommands\baseCommands\setSettings',

			'isDeclaringState' => '\spectrum\constructionCommands\baseCommands\isDeclaringState',
			'isRunningState' => '\spectrum\constructionCommands\baseCommands\isRunningState',
		), Manager::getRegisteredCommands());
	}

	public function testCallStatic_ShouldBeCallRegisteredCommandAndPassArgumentsToCallback()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', function($a, $b) use(&$passedA, &$passedB){
			$passedA = $a;
			$passedB = $b;
		});

		Manager::foo('aaa', 'bbb');

		$this->assertEquals('aaa', $passedA);
		$this->assertEquals('bbb', $passedB);
	}

	public function testCallStatic_ShouldBeCallRegisteredCommandAndReturnCallbackResult()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', function(){ return 'bar'; });
		$this->assertEquals('bar', Manager::foo());
	}

	public function testCallCommand_ShouldBePassArgumentsToRegisteredCommandCallback()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', function($a, $b) use(&$passedA, &$passedB){
			$passedA = $a;
			$passedB = $b;
		});

		Manager::callCommand('foo', array('aaa', 'bbb'));

		$this->assertEquals('aaa', $passedA);
		$this->assertEquals('bbb', $passedB);
	}

	public function testCallCommand_ShouldBeReturnRegisteredCommandResult()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', function(){ return 'bar'; });
		$this->assertEquals('bar', Manager::callCommand('foo'));
	}

/**/

	public function testRegisterCommand_ShouldBeCollectCommands()
	{
		Manager::unregisterAllCommands();

		$this->assertSame(array(), Manager::getRegisteredCommands());

		$function1 = function(){};
		$function2 = function(){};
		$function3 = 'testFunc';

		Manager::registerCommand('foo', $function1);
		$this->assertSame(array(
			'foo' => $function1,
		), Manager::getRegisteredCommands());

		Manager::registerCommand('bar', $function2);
		$this->assertSame(array(
			'foo' => $function1,
			'bar' => $function2,
		), Manager::getRegisteredCommands());

		Manager::registerCommand('baz', $function3);
		$this->assertSame(array(
			'foo' => $function1,
			'bar' => $function2,
			'baz' => $function3,
		), Manager::getRegisteredCommands());
	}

	public function testRegisterCommand_ShouldBeReplaceExistsCommand()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', 'fooFunc');
		Manager::registerCommand('foo', 'barFunc');

		$this->assertSame(
			array('foo' => 'barFunc')
			, Manager::getRegisteredCommands()
		);
	}

	public function testRegisterCommand_ShouldBeAcceptClosureFunction()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', function(){ return 'bar'; });

		$this->assertEquals('bar', Manager::foo());
	}

	public function testRegisterCommand_ShouldBeAcceptCreatedAnonymousFunction()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', create_function('', 'return "bar";'));

		$this->assertEquals('bar', Manager::foo());
	}

	public function testRegisterCommand_ShouldBeAcceptUserDefinedFunction()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', __CLASS__ . '::myCommand');

		$this->assertEquals('bar', Manager::foo());
	}

	public function testRegisterCommand_ShouldBeAcceptCallbackArray()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', array(__CLASS__, 'myCommand'));

		$this->assertEquals('bar', Manager::foo());
	}

	public function testRegisterCommand_ShouldBeThrowExceptionIfCommandNameIsNotValidFunctionName()
	{
		Manager::unregisterAllCommands();
		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'Bad name', function(){
			Manager::registerCommand('-foo', function(){});
		});
	}

	public function testRegisterCommand_ShouldBeThrowExceptionIfNotAllowConstructionCommandsRegistration()
	{
		Manager::unregisterAllCommands();
		Config::setAllowConstructionCommandsRegistration(false);
		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'Construction commands registration deny', function(){
			Manager::registerCommand('foo', function(){});
		});
	}

	public function testRegisterCommand_ShouldBeThrowExceptionIfCommandExistsAndNotAllowConstructionCommandsOverride()
	{
		Manager::unregisterAllCommands();
		Config::setAllowConstructionCommandsOverride(false);
		Manager::registerCommand('foo', function(){});
		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'Construction commands override deny', function(){
			Manager::registerCommand('foo', function(){});
		});
	}

	public function testRegisterCommands_ShouldBeAcceptArrayWithCommandNameAndCallback()
	{
		Manager::unregisterAllCommands();

		$function1 = function(){};
		$function2 = function(){};
		$function3 = 'testFunc';

		Manager::registerCommands(array(
			'foo' => $function1,
			'bar' => $function2,
			'baz' => $function3,
		));

		$this->assertSame(array(
			'foo' => $function1,
			'bar' => $function2,
			'baz' => $function3,
		), Manager::getRegisteredCommands());
	}

/**/

	public function testUnregisterCommand_ShouldBeRemoveCommandByName()
	{
		Manager::unregisterAllCommands();

		Manager::registerCommand('foo', function(){});
		Manager::unregisterCommand('foo');

		$this->assertFalse(Manager::hasRegisteredCommand('foo'));
		$this->assertSame(array(), Manager::getRegisteredCommands());
	}

	public function testUnregisterCommand_ShouldBeThrowExceptionIfNotAllowConstructionCommandsOverride()
	{
		Manager::unregisterAllCommands();
		Config::setAllowConstructionCommandsOverride(false);
		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'Construction commands override deny', function(){
			Manager::unregisterCommand('foo');
		});
	}

/**/

	public function testUnregisterAllCommands_ShouldBeLeaveEmptyArray()
	{
		Manager::registerCommand('foo', function(){});
		Manager::unregisterAllCommands();
		$this->assertSame(array(), Manager::getRegisteredCommands());
	}

	public function testUnregisterAllCommands_ShouldBeThrowExceptionIfNotAllowConstructionCommandsOverride()
	{
		Manager::unregisterAllCommands();
		Config::setAllowConstructionCommandsOverride(false);
		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'Construction commands override deny', function(){
			Manager::unregisterAllCommands('foo');
		});
	}

/**/

	public function testHasRegisteredCommand_ShouldBeReturnTrueIfCommandExists()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', function(){});
		$this->assertTrue(Manager::hasRegisteredCommand('foo'));
	}

	public function testHasRegisteredCommand_ShouldBeReturnFalseIfCommandNotExists()
	{
		Manager::unregisterAllCommands();
		$this->assertFalse(Manager::hasRegisteredCommand('foo'));
	}

	public function testGetRegisteredCommandCallback_ShouldBeReturnCallbackByCommandName()
	{
		Manager::unregisterAllCommands();
		$function = function(){};
		Manager::registerCommand('foo', $function);

		$this->assertSame($function, Manager::getRegisteredCommandCallback('foo'));
	}

	public function testGetRegisteredCommandCallback_ShouldBeThrowExceptionIfCommandNotExists()
	{
		Manager::unregisterAllCommands();

		$this->assertThrowException('\spectrum\constructionCommands\Exception', function(){
			Manager::getRegisteredCommandCallback('foo');
		});
	}

/**/

	public function myCommand()
	{
		return 'bar';
	}
}