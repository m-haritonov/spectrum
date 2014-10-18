<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\core;

use spectrum\config;
use spectrum\core\Assertion;
use spectrum\core\details\MatcherCall;
use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class AssertionTest extends \spectrum\tests\Test {
	public function testMatcherCall_GetsMatcherCallDetailsClassFromConfig() {
		$matcherCallDetailsClassName = $this->createClass('class ... extends \spectrum\core\details\MatcherCall {}');
		config::setClassReplacement('\spectrum\core\details\MatcherCall', $matcherCallDetailsClassName);

		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), null);
			$assert->zzz();
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return true; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertInstanceOf($matcherCallDetailsClassName, $results[0]['details']);
	}
	
	public function testMatcherCall_GetsMatcherFunctionFromRunningAncestorOfOwnerSpecOrFromOwnerSpec() {
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa");
			$assert->zzz();
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->Spec(parent3)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2', 'parent2' => 'endingSpec2'));
		
		$calls = array();
		$specs[0]->matchers->add('zzz', function() use(&$calls){ $calls[] = 'aaa'; });
		$specs['endingSpec1']->matchers->add('zzz', function() use(&$calls){ $calls[] = 'bbb'; });
		$specs['parent1']->matchers->add('zzz', function() use(&$calls){ $calls[] = 'ccc'; });
		$specs['parent2']->matchers->add('zzz', function() use(&$calls){ $calls[] = 'ddd'; });
		
		$specs[0]->run();
		
		$this->assertSame(array('bbb', 'ccc', 'ddd', 'aaa'), $calls);
	}
	
	public function testMatcherCall_PassesMatcherCallDetailsAndTestedValueAndArgumentsToMatcher() {
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa");
			$assert->zzz("bbb", "ccc", "ddd");
		', 'onEndingSpecExecute');

		$passedArguments = array();
		$spec = new Spec();
		$spec->matchers->add('zzz', function() use(&$passedArguments){
			$passedArguments[] = func_get_args();
		});
		
		$spec->run();
		$this->assertTrue($passedArguments[0][0] instanceof MatcherCall);
		$this->assertSame(array(array($passedArguments[0][0], 'aaa', 'bbb', 'ccc', 'ddd')), $passedArguments);
	}
	
	public function testMatcherCall_ReturnsAssertionInstance() {
		\spectrum\tests\Test::$temp["assertion"] = null;
		\spectrum\tests\Test::$temp["returnValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["assertion"] = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa");
			\spectrum\tests\Test::$temp["returnValue"] = \spectrum\tests\Test::$temp["assertion"]->zzz();
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){});
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Assertion', \spectrum\tests\Test::$temp["returnValue"]);
		$this->assertSame(\spectrum\tests\Test::$temp["assertion"], \spectrum\tests\Test::$temp["returnValue"]);
	}
	
	public function testMatcherCall_ResetsNotFlagAfterCall() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), null);
			$assert->not->zzz();
			$assert->zzz();
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return true; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(2, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertSame(true, $results[1]['result']);
	}
	
	public function testMatcherCall_SupportsChainCall() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
			
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), null);
			$assert
				->aaa()
				->bbb()
				->not->aaa()
				->not->bbb()
				->bbb();
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->matchers->add('aaa', function(){ return true; });
		$spec->matchers->add('bbb', function(){ return false; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(5, count($results));
		$this->assertSame(true, $results[0]['result']);
		$this->assertSame(false, $results[1]['result']);
		$this->assertSame(false, $results[2]['result']);
		$this->assertSame(true, $results[3]['result']);
		$this->assertSame(false, $results[4]['result']);
	}
	
	public function testMatcherCall_MatcherReturnsFalse_AddsFalseWithMatcherCallDetailsToResultBuffer() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		\spectrum\tests\Test::$temp["file"] = false;
		\spectrum\tests\Test::$temp["line"] = false;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa bbb");
			$assert->zzz("ccc", "ddd", "eee"); \spectrum\tests\Test::$temp["line"] = __LINE__;
			\spectrum\tests\Test::$temp["file"] = __FILE__;
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return false; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		
		$this->assertSame(false, $results[0]['result']);
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $results[0]['details']);
		$this->assertSame('aaa bbb', $results[0]['details']->getTestedValue());
		$this->assertSame(false, $results[0]['details']->getNot());
		$this->assertSame(false, $results[0]['details']->getResult());
		$this->assertSame('zzz', $results[0]['details']->getMatcherName());
		$this->assertSame(array('ccc', 'ddd', 'eee'), $results[0]['details']->getMatcherArguments());
		$this->assertSame(false, $results[0]['details']->getMatcherReturnValue());
		$this->assertSame(null, $results[0]['details']->getMatcherException());
		$this->assertSame(\spectrum\tests\Test::$temp["file"], $results[0]['details']->getFile());
		$this->assertSame(\spectrum\tests\Test::$temp["line"], $results[0]['details']->getLine());
	}
	
	public function testMatcherCall_MatcherReturnsFalse_CastsResultToFalse() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa bbb");
			$assert->zzz();
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return 0; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertSame(false, $results[0]['details']->getResult());
		$this->assertSame(0, $results[0]['details']->getMatcherReturnValue());
	}
	
	public function testMatcherCall_MatcherReturnsFalse_DoesNotBreakExecution() {
		\spectrum\tests\Test::$temp["isExecuted"] = false;
		
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa bbb");
			$assert->zzz();
			\spectrum\tests\Test::$temp["isExecuted"] = true;
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return false; });
		$spec->run();
		
		$this->assertSame(true, \spectrum\tests\Test::$temp["isExecuted"]);
	}
	
	public function testMatcherCall_MatcherReturnsFalse_NotFlagEnabled_AddsTrueWithMatcherCallDetailsToResultBuffer() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		\spectrum\tests\Test::$temp["file"] = false;
		\spectrum\tests\Test::$temp["line"] = false;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa bbb");
			$assert->not->zzz("ccc", "ddd", "eee"); \spectrum\tests\Test::$temp["line"] = __LINE__;
			\spectrum\tests\Test::$temp["file"] = __FILE__;
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return false; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		
		$this->assertSame(true, $results[0]['result']);
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $results[0]['details']);
		$this->assertSame('aaa bbb', $results[0]['details']->getTestedValue());
		$this->assertSame(true, $results[0]['details']->getNot());
		$this->assertSame(true, $results[0]['details']->getResult());
		$this->assertSame('zzz', $results[0]['details']->getMatcherName());
		$this->assertSame(array('ccc', 'ddd', 'eee'), $results[0]['details']->getMatcherArguments());
		$this->assertSame(false, $results[0]['details']->getMatcherReturnValue());
		$this->assertSame(null, $results[0]['details']->getMatcherException());
		$this->assertSame(\spectrum\tests\Test::$temp["file"], $results[0]['details']->getFile());
		$this->assertSame(\spectrum\tests\Test::$temp["line"], $results[0]['details']->getLine());
	}
	
	public function testMatcherCall_MatcherReturnsTrue_AddsTrueWithMatcherCallDetailsToResultBuffer() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		\spectrum\tests\Test::$temp["file"] = false;
		\spectrum\tests\Test::$temp["line"] = false;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa bbb");
			$assert->zzz("ccc", "ddd", "eee"); \spectrum\tests\Test::$temp["line"] = __LINE__;
			\spectrum\tests\Test::$temp["file"] = __FILE__;
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return true; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		
		$this->assertSame(true, $results[0]['result']);
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $results[0]['details']);
		$this->assertSame('aaa bbb', $results[0]['details']->getTestedValue());
		$this->assertSame(false, $results[0]['details']->getNot());
		$this->assertSame(true, $results[0]['details']->getResult());
		$this->assertSame('zzz', $results[0]['details']->getMatcherName());
		$this->assertSame(array('ccc', 'ddd', 'eee'), $results[0]['details']->getMatcherArguments());
		$this->assertSame(true, $results[0]['details']->getMatcherReturnValue());
		$this->assertSame(null, $results[0]['details']->getMatcherException());
		$this->assertSame(\spectrum\tests\Test::$temp["file"], $results[0]['details']->getFile());
		$this->assertSame(\spectrum\tests\Test::$temp["line"], $results[0]['details']->getLine());
	}
	
	public function testMatcherCall_MatcherReturnsTrue_CastsResultToTrue() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa bbb");
			$assert->zzz();
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return 1; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(true, $results[0]['result']);
		$this->assertSame(true, $results[0]['details']->getResult());
		$this->assertSame(1, $results[0]['details']->getMatcherReturnValue());
	}
	
	public function testMatcherCall_MatcherReturnsTrue_DoesNotBreakExecution() {
		\spectrum\tests\Test::$temp["isExecuted"] = false;
		
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa bbb");
			$assert->zzz();
			\spectrum\tests\Test::$temp["isExecuted"] = true;
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return true; });
		$spec->run();
		
		$this->assertSame(true, \spectrum\tests\Test::$temp["isExecuted"]);
	}
	
	public function testMatcherCall_MatcherReturnsTrue_NotFlagEnabled_AddsFalseWithMatcherCallDetailsToResultBuffer() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		\spectrum\tests\Test::$temp["file"] = false;
		\spectrum\tests\Test::$temp["line"] = false;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa bbb");
			$assert->not->zzz("ccc", "ddd", "eee"); \spectrum\tests\Test::$temp["line"] = __LINE__;
			\spectrum\tests\Test::$temp["file"] = __FILE__;
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return true; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		
		$this->assertSame(false, $results[0]['result']);
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $results[0]['details']);
		$this->assertSame('aaa bbb', $results[0]['details']->getTestedValue());
		$this->assertSame(true, $results[0]['details']->getNot());
		$this->assertSame(false, $results[0]['details']->getResult());
		$this->assertSame('zzz', $results[0]['details']->getMatcherName());
		$this->assertSame(array('ccc', 'ddd', 'eee'), $results[0]['details']->getMatcherArguments());
		$this->assertSame(true, $results[0]['details']->getMatcherReturnValue());
		$this->assertSame(null, $results[0]['details']->getMatcherException());
		$this->assertSame(\spectrum\tests\Test::$temp["file"], $results[0]['details']->getFile());
		$this->assertSame(\spectrum\tests\Test::$temp["line"], $results[0]['details']->getLine());
	}
	
	public function testMatcherCall_MatcherThrowsException_AddsFalseWithMatcherCallDetailsToResultBuffer() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		\spectrum\tests\Test::$temp["file"] = false;
		\spectrum\tests\Test::$temp["line"] = false;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa bbb");
			$assert->zzz("ccc", "ddd", "eee"); \spectrum\tests\Test::$temp["line"] = __LINE__;
			\spectrum\tests\Test::$temp["file"] = __FILE__;
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$exception = new \Exception('Something wrong');
		$spec->matchers->add('zzz', function() use($exception){ throw $exception; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		
		$this->assertSame(false, $results[0]['result']);
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $results[0]['details']);
		$this->assertSame('aaa bbb', $results[0]['details']->getTestedValue());
		$this->assertSame(false, $results[0]['details']->getNot());
		$this->assertSame(false, $results[0]['details']->getResult());
		$this->assertSame('zzz', $results[0]['details']->getMatcherName());
		$this->assertSame(array('ccc', 'ddd', 'eee'), $results[0]['details']->getMatcherArguments());
		$this->assertSame(null, $results[0]['details']->getMatcherReturnValue());
		$this->assertSame($exception, $results[0]['details']->getMatcherException());
		$this->assertSame('Something wrong', $results[0]['details']->getMatcherException()->getMessage());
		$this->assertSame(\spectrum\tests\Test::$temp["file"], $results[0]['details']->getFile());
		$this->assertSame(\spectrum\tests\Test::$temp["line"], $results[0]['details']->getLine());
	}
	
	public function testMatcherCall_MatcherThrowsException_DoesNotBreakExecution() {
		\spectrum\tests\Test::$temp["isExecuted"] = false;
		
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa bbb");
			$assert->zzz();
			\spectrum\tests\Test::$temp["isExecuted"] = true;
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ throw new \Exception(); });
		$spec->run();
		
		$this->assertSame(true, \spectrum\tests\Test::$temp["isExecuted"]);
	}
	
	public function testMatcherCall_MatcherThrowsException_NotFlagEnabled_DoesNotInvertResultAndAddsFalseWithMatcherCallDetailsToResultBuffer() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		\spectrum\tests\Test::$temp["file"] = false;
		\spectrum\tests\Test::$temp["line"] = false;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa bbb");
			$assert->not->zzz("ccc", "ddd", "eee"); \spectrum\tests\Test::$temp["line"] = __LINE__;
			\spectrum\tests\Test::$temp["file"] = __FILE__;
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$exception = new \Exception('Something wrong');
		$spec->matchers->add('zzz', function() use($exception){ throw $exception; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		
		$this->assertSame(false, $results[0]['result']);
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $results[0]['details']);
		$this->assertSame('aaa bbb', $results[0]['details']->getTestedValue());
		$this->assertSame(true, $results[0]['details']->getNot());
		$this->assertSame(false, $results[0]['details']->getResult());
		$this->assertSame('zzz', $results[0]['details']->getMatcherName());
		$this->assertSame(array('ccc', 'ddd', 'eee'), $results[0]['details']->getMatcherArguments());
		$this->assertSame(null, $results[0]['details']->getMatcherReturnValue());
		$this->assertSame($exception, $results[0]['details']->getMatcherException());
		$this->assertSame('Something wrong', $results[0]['details']->getMatcherException()->getMessage());
		$this->assertSame(\spectrum\tests\Test::$temp["file"], $results[0]['details']->getFile());
		$this->assertSame(\spectrum\tests\Test::$temp["line"], $results[0]['details']->getLine());
	}
	
	public function testMatcherCall_MatcherNotExists_AddsFalseResultToResultBuffer() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa");
			$assert->zzz();
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->matchers->remove('zzz');
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\Exception', $results[0]['details']);
		$this->assertSame('Matcher "zzz" not exists', $results[0]['details']->getMessage());
	}
	
	public function testMatcherCall_MatcherNotExists_DoesNotBreakExecution() {
		\spectrum\tests\Test::$temp["isExecuted"] = false;
		
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa");
			$assert->zzz();
			\spectrum\tests\Test::$temp["isExecuted"] = true;
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->matchers->remove('zzz');
		$spec->run();
		
		$this->assertSame(true, \spectrum\tests\Test::$temp["isExecuted"]);
	}
	
	public function testMatcherCall_MatcherNotExists_ReturnsAssertionInstance() {
		\spectrum\tests\Test::$temp["assertion"] = null;
		\spectrum\tests\Test::$temp["returnValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["assertion"] = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa");
			\spectrum\tests\Test::$temp["returnValue"] = \spectrum\tests\Test::$temp["assertion"]->zzz();
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->matchers->remove('zzz');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Assertion', \spectrum\tests\Test::$temp["returnValue"]);
		$this->assertSame(\spectrum\tests\Test::$temp["assertion"], \spectrum\tests\Test::$temp["returnValue"]);
	}

	public function testMatcherCall_CallOnNotRun_ThrowsExceptionAndDoesNotCallMatcher() {
		$spec = new Spec();
		$spec->setName('aaa');
		
		$isCalled = false;
		$spec->matchers->add('zzz', function() use(&$isCalled) {
			$isCalled = true;
		});
		
		$assert = new Assertion($spec, null);
		$this->assertThrowsException('\spectrum\Exception', 'Matcher call is denied on not running spec (now spec "aaa" is not running)', function() use($assert){
			$assert->zzz();
		});
		
		$this->assertSame(false, $isCalled);
	}
	
	public function testEventDispatch_OnMatcherCallStart_IsDispatchedBeforeMatcherCall() {
		\spectrum\tests\Test::$temp["calls"] = array();
		\spectrum\config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Plugin {
				static public function getEventListeners() {
					return array(
						array("event" => "onMatcherCallStart", "method" => "onMatcherCallStart", "order" => 100),
					);
				}
				
				public function onMatcherCallStart($matcherCallDetails) {
					\spectrum\tests\Test::$temp["calls"][] = "event";
				}
			}
		'));
		
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa");
			$assert->zzz();
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ \spectrum\tests\Test::$temp["calls"][] = "matcher"; });
		$spec->run();
		
		$this->assertSame(array('event', 'matcher'), \spectrum\tests\Test::$temp["calls"]);
	}
	
	public function testEventDispatch_OnMatcherCallStart_PassesMatcherCallDetailsToCalleeMethod() {
		\spectrum\tests\Test::$temp["matcherCallDetails"] = null;
		\spectrum\tests\Test::$temp["file"] = false;
		\spectrum\tests\Test::$temp["line"] = false;
		
		\spectrum\config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Plugin {
				static public function getEventListeners() {
					return array(
						array("event" => "onMatcherCallStart", "method" => "onMatcherCallStart", "order" => 100),
					);
				}
				
				public function onMatcherCallStart($matcherCallDetails) {
					\spectrum\tests\Test::$temp["matcherCallDetails"] = $matcherCallDetails;
				}
			}
		'));
		
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa");
			$assert->zzz("bbb", "ccc", "ddd"); \spectrum\tests\Test::$temp["line"] = __LINE__;
			\spectrum\tests\Test::$temp["file"] = __FILE__;
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return 'rrr'; });
		$spec->run();
		
		$matcherCallDetails = \spectrum\tests\Test::$temp["matcherCallDetails"];
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $matcherCallDetails);
		$this->assertSame('aaa', $matcherCallDetails->getTestedValue());
		$this->assertSame(false, $matcherCallDetails->getNot());
		$this->assertSame(true, $matcherCallDetails->getResult());
		$this->assertSame('zzz', $matcherCallDetails->getMatcherName());
		$this->assertSame(array('bbb', 'ccc', 'ddd'), $matcherCallDetails->getMatcherArguments());
		$this->assertSame('rrr', $matcherCallDetails->getMatcherReturnValue());
		$this->assertSame(null, $matcherCallDetails->getMatcherException());
		$this->assertSame(\spectrum\tests\Test::$temp["file"], $matcherCallDetails->getFile());
		$this->assertSame(\spectrum\tests\Test::$temp["line"], $matcherCallDetails->getLine());
	}
	
	public function testEventDispatch_OnMatcherCallFinish_IsDispatchedAfterMatcherCall() {
		\spectrum\tests\Test::$temp["calls"] = array();
		\spectrum\config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Plugin {
				static public function getEventListeners() {
					return array(
						array("event" => "onMatcherCallFinish", "method" => "onMatcherCallFinish", "order" => 100),
					);
				}
				
				public function onMatcherCallFinish($matcherCallDetails) {
					\spectrum\tests\Test::$temp["calls"][] = "event";
				}
			}
		'));
		
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa");
			$assert->zzz();
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ \spectrum\tests\Test::$temp["calls"][] = "matcher"; });
		$spec->run();
		
		$this->assertSame(array('matcher', 'event'), \spectrum\tests\Test::$temp["calls"]);
	}
	
	public function testEventDispatch_OnMatcherCallFinish_IsDispatchedAfterResultAddToResultBuffer() {
		\spectrum\tests\Test::$temp["results"] = null;
		\spectrum\config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Plugin {
				static public function getEventListeners() {
					return array(
						array("event" => "onMatcherCallFinish", "method" => "onMatcherCallFinish", "order" => 100),
					);
				}
				
				public function onMatcherCallFinish($matcherCallDetails) {
					\spectrum\tests\Test::$temp["results"] = $this->getOwnerSpec()->getResultBuffer()->getResults();
				}
			}
		'));
		
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa");
			$assert->zzz();
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return false; });
		$spec->run();
		
		$this->assertSame(1, count(\spectrum\tests\Test::$temp["results"]));
		$this->assertSame(false, \spectrum\tests\Test::$temp["results"][0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', \spectrum\tests\Test::$temp["results"][0]['details']);
		$this->assertSame('zzz', \spectrum\tests\Test::$temp["results"][0]['details']->getMatcherName());
	}
	
	public function testEventDispatch_OnMatcherCallFinish_IsDispatchedAfterNotFlagReset() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		\spectrum\tests\Test::$temp["assertion"] = null;
		\spectrum\tests\Test::$temp["isCalled"] = false;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		
			\spectrum\tests\Test::$temp["assertion"] = new \spectrum\core\Assertion($this->getOwnerSpec(), null);
			\spectrum\tests\Test::$temp["assertion"]->not->zzz();
		', 'onEndingSpecExecute');
		
		$this->registerPluginWithCodeInEvent('
			if (!\spectrum\tests\Test::$temp["isCalled"]) {
				\spectrum\tests\Test::$temp["isCalled"] = true;
				\spectrum\tests\Test::$temp["assertion"]->zzz();
			}
		', 'onMatcherCallFinish');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return true; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(2, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertSame(true, $results[1]['result']);
	}
	
	public function testEventDispatch_OnMatcherCallFinish_PassesMatcherCallDetailsToCalleeMethod() {
		\spectrum\tests\Test::$temp["matcherCallDetails"] = null;
		\spectrum\tests\Test::$temp["file"] = false;
		\spectrum\tests\Test::$temp["line"] = false;
		
		\spectrum\config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Plugin {
				static public function getEventListeners() {
					return array(
						array("event" => "onMatcherCallFinish", "method" => "onMatcherCallFinish", "order" => 100),
					);
				}
				
				public function onMatcherCallFinish($matcherCallDetails) {
					\spectrum\tests\Test::$temp["matcherCallDetails"] = $matcherCallDetails;
				}
			}
		'));
		
		$this->registerPluginWithCodeInEvent('
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa");
			$assert->zzz("bbb", "ccc", "ddd"); \spectrum\tests\Test::$temp["line"] = __LINE__;
			\spectrum\tests\Test::$temp["file"] = __FILE__;
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return 'rrr'; });
		$spec->run();
		
		$matcherCallDetails = \spectrum\tests\Test::$temp["matcherCallDetails"];
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $matcherCallDetails);
		$this->assertSame('aaa', $matcherCallDetails->getTestedValue());
		$this->assertSame(false, $matcherCallDetails->getNot());
		$this->assertSame(true, $matcherCallDetails->getResult());
		$this->assertSame('zzz', $matcherCallDetails->getMatcherName());
		$this->assertSame(array('bbb', 'ccc', 'ddd'), $matcherCallDetails->getMatcherArguments());
		$this->assertSame('rrr', $matcherCallDetails->getMatcherReturnValue());
		$this->assertSame(null, $matcherCallDetails->getMatcherException());
		$this->assertSame(\spectrum\tests\Test::$temp["file"], $matcherCallDetails->getFile());
		$this->assertSame(\spectrum\tests\Test::$temp["line"], $matcherCallDetails->getLine());
	}
	
/**/
	
	public function testPropertyAccess_PropertyNotExists_ThrowsException() {
		$assert = new \spectrum\core\Assertion(new Spec(), null);
		$this->assertThrowsException('\spectrum\Exception', 'Undefined property "aaa" in "\spectrum\core\Assertion" class', function() use($assert){
			$assert->aaa;
		});
	}
		
	public function testPropertyAccess_Not_InvertsNotFlag() {
		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
		
			$assert = new \spectrum\core\Assertion($this->getOwnerSpec(), null);
			$assert->not->zzz();
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->matchers->add('zzz', function(){ return true; });
		$spec->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
	}
	
	public function testPropertyAccess_Not_ReturnsAssertionInstance() {
		\spectrum\tests\Test::$temp["assertion"] = null;
		\spectrum\tests\Test::$temp["returnValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["assertion"] = new \spectrum\core\Assertion($this->getOwnerSpec(), "aaa");
			\spectrum\tests\Test::$temp["returnValue"] = \spectrum\tests\Test::$temp["assertion"]->not;
		', 'onEndingSpecExecute');

		$spec = new Spec();
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Assertion', \spectrum\tests\Test::$temp["returnValue"]);
		$this->assertSame(\spectrum\tests\Test::$temp["assertion"], \spectrum\tests\Test::$temp["returnValue"]);
	}
}