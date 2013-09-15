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

class ManagerTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::unregisterAllSpecPlugins();
	}
	
	public function testHasRegisteredCommandsByDefault()
	{
		$this->restoreStaticProperties('\spectrum\config');
		
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

	public function testCallStatic_CallsRegisteredCommandAndPassArgumentsToCallback()
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

	public function testCallStatic_CallsRegisteredCommandAndReturnCallbackResult()
	{
		manager::unregisterAllCommands();
		manager::registerCommand('foo', function(){ return 'bar'; });
		$this->assertEquals('bar', manager::foo());
	}

	public function testCallCommand_PassesArgumentsToRegisteredCommandCallback()
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

	public function testCallCommand_ReturnsRegisteredCommandResult()
	{
		manager::unregisterAllCommands();
		manager::registerCommand('foo', function(){ return 'bar'; });
		$this->assertEquals('bar', manager::callCommand('foo'));
	}
}