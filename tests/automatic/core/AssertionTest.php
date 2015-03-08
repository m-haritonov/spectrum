<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core;

use spectrum\config;
use spectrum\core\Assertion;
use spectrum\core\AssertionInterface;
use spectrum\core\details\MatcherCall;
use spectrum\core\Spec;
use spectrum\core\SpecInterface;

require_once __DIR__ . '/../../init.php';

class AssertionTest extends \spectrum\tests\automatic\Test {
	public function testMatcherCall_GetsMatcherCallDetailsClassFromConfig() {
		$matcherCallDetailsClassName = \spectrum\tests\_testware\tools::createClass('class ... extends \spectrum\core\details\MatcherCall {}');
		config::setClassReplacement('\spectrum\core\details\MatcherCall', $matcherCallDetailsClassName);

		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return true; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results) {
			$results = $spec->getResults();
			$assert = new Assertion($spec, null);
			$assert->zzz();
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertInstanceOf($matcherCallDetailsClassName, $resultsContent[0]['details']);
	}
	
	public function testMatcherCall_GetsMatcherFunctionFromRunningAncestorOfOwnerSpecOrFromOwnerSpec() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->Spec(parent3)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2', 'parent2' => 'endingSpec2'));
		
		$calls = array();
		$specs[0]->getMatchers()->add('zzz', function() use(&$calls){ $calls[] = 'aaa'; });
		$specs['endingSpec1']->getMatchers()->add('zzz', function() use(&$calls){ $calls[] = 'bbb'; });
		$specs['parent1']->getMatchers()->add('zzz', function() use(&$calls){ $calls[] = 'ccc'; });
		$specs['parent2']->getMatchers()->add('zzz', function() use(&$calls){ $calls[] = 'ddd'; });
		
		$specs[0]->getExecutor()->setFunction(function() use(&$specs) {
			$assert = new Assertion($specs[0]->getRunningDescendantEndingSpec(), "aaa");
			$assert->zzz();
		});
		
		$specs[0]->run();
		
		$this->assertSame(array('bbb', 'ccc', 'ddd', 'aaa'), $calls);
	}
	
	public function testMatcherCall_PassesMatcherCallDetailsAndTestedValueAndArgumentsToMatcher() {
		$passedArguments = array();
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function() use(&$passedArguments){
			$passedArguments[] = func_get_args();
		});
		$spec->getExecutor()->setFunction(function() use(&$spec) {
			$assert = new Assertion($spec, "aaa");
			$assert->zzz("bbb", "ccc", "ddd");
		});
		
		$spec->run();
		$this->assertTrue($passedArguments[0][0] instanceof MatcherCall);
		$this->assertSame(array(array($passedArguments[0][0], 'aaa', 'bbb', 'ccc', 'ddd')), $passedArguments);
	}
	
	public function testMatcherCall_ReturnsAssertionInstance() {
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){});
		$spec->getExecutor()->setFunction(function() use($spec, &$assertion, &$returnValue) {
			$assertion = new Assertion($spec, "aaa");
			$returnValue = $assertion->zzz();
		});
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Assertion', $returnValue);
		$this->assertSame($assertion, $returnValue);
	}
	
	public function testMatcherCall_ResetsNotFlagAfterCall() {
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return true; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results) {
			$results = $spec->getResults();
		
			$assert = new Assertion($spec, null);
			$assert->not->zzz();
			$assert->zzz();
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(2, count($resultsContent));
		$this->assertSame(false, $resultsContent[0]['result']);
		$this->assertSame(true, $resultsContent[1]['result']);
	}
	
	public function testMatcherCall_SupportsChainCall() {
		$spec = new Spec();
		$spec->getMatchers()->add('aaa', function(){ return true; });
		$spec->getMatchers()->add('bbb', function(){ return false; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results) {
			$results = $spec->getResults();
			
			$assert = new Assertion($spec, null);
			$assert
				->aaa()
				->bbb()
				->not->aaa()
				->not->bbb()
				->bbb();
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(5, count($resultsContent));
		$this->assertSame(true, $resultsContent[0]['result']);
		$this->assertSame(false, $resultsContent[1]['result']);
		$this->assertSame(false, $resultsContent[2]['result']);
		$this->assertSame(true, $resultsContent[3]['result']);
		$this->assertSame(false, $resultsContent[4]['result']);
	}
	
	public function testMatcherCall_MatcherReturnsFalse_AddsFalseWithMatcherCallDetailsToResults() {
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return false; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results, &$file, &$line) {
			$results = $spec->getResults();
		
			$assert = new Assertion($spec, "aaa bbb");
			$assert->zzz("ccc", "ddd", "eee"); $line = __LINE__;
			$file = __FILE__;
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(1, count($resultsContent));
		
		$this->assertSame(false, $resultsContent[0]['result']);
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $resultsContent[0]['details']);
		$this->assertSame('aaa bbb', $resultsContent[0]['details']->getTestedValue());
		$this->assertSame(false, $resultsContent[0]['details']->getNot());
		$this->assertSame(false, $resultsContent[0]['details']->getResult());
		$this->assertSame('zzz', $resultsContent[0]['details']->getMatcherName());
		$this->assertSame(array('ccc', 'ddd', 'eee'), $resultsContent[0]['details']->getMatcherArguments());
		$this->assertSame(false, $resultsContent[0]['details']->getMatcherReturnValue());
		$this->assertSame(null, $resultsContent[0]['details']->getMatcherException());
		$this->assertSame($file, $resultsContent[0]['details']->getFile());
		$this->assertSame($line, $resultsContent[0]['details']->getLine());
	}
	
	public function testMatcherCall_MatcherReturnsFalse_CastsResultToFalse() {
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return 0; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results) {
			$results = $spec->getResults();
			$assert = new Assertion($spec, "aaa bbb");
			$assert->zzz();
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(1, count($resultsContent));
		$this->assertSame(false, $resultsContent[0]['result']);
		$this->assertSame(false, $resultsContent[0]['details']->getResult());
		$this->assertSame(0, $resultsContent[0]['details']->getMatcherReturnValue());
	}
	
	public function testMatcherCall_MatcherReturnsFalse_DoesNotBreakExecution() {
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return false; });
		$spec->getExecutor()->setFunction(function() use($spec, &$isExecuted) {
			$assert = new Assertion($spec, "aaa bbb");
			$assert->zzz();
			$isExecuted = true;
		});
		$spec->run();
		
		$this->assertSame(true, $isExecuted);
	}
	
	public function testMatcherCall_MatcherReturnsFalse_NotFlagIsEnabled_AddsTrueWithMatcherCallDetailsToResults() {
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return false; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results, &$file, &$line) {
			$results = $spec->getResults();
		
			$assert = new Assertion($spec, "aaa bbb");
			$assert->not->zzz("ccc", "ddd", "eee"); $line = __LINE__;
			$file = __FILE__;
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(1, count($resultsContent));
		
		$this->assertSame(true, $resultsContent[0]['result']);
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $resultsContent[0]['details']);
		$this->assertSame('aaa bbb', $resultsContent[0]['details']->getTestedValue());
		$this->assertSame(true, $resultsContent[0]['details']->getNot());
		$this->assertSame(true, $resultsContent[0]['details']->getResult());
		$this->assertSame('zzz', $resultsContent[0]['details']->getMatcherName());
		$this->assertSame(array('ccc', 'ddd', 'eee'), $resultsContent[0]['details']->getMatcherArguments());
		$this->assertSame(false, $resultsContent[0]['details']->getMatcherReturnValue());
		$this->assertSame(null, $resultsContent[0]['details']->getMatcherException());
		$this->assertSame($file, $resultsContent[0]['details']->getFile());
		$this->assertSame($line, $resultsContent[0]['details']->getLine());
	}
	
	public function testMatcherCall_MatcherReturnsFalse_BreakOnFirstMatcherFailIsTrue_BreaksExecution() {
		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstMatcherFail(true);
		$spec->getMatchers()->add('zzz', function(){ return false; });
		$spec->getExecutor()->setFunction(function() use($spec, &$isExecuted) {
			$assert = new Assertion($spec, "aaa");
			$assert->zzz();
			$isExecuted = true;
		});
		$spec->run();
		$this->assertSame(null, $isExecuted);
	}
	
	public function testMatcherCall_MatcherReturnsFalse_GetsValueFromAncestorOrSelf() {
		$callCount = -1;
		$isExecuted = array();
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
			->Spec
			->->Spec
		', array(3 => 5));
		
		$specs[0]->getErrorHandling()->setCatchPhpErrors(0);
		$specs[0]->getMatchers()->add('failMatcher', function(){ return false; });
		$specs[0]->getExecutor()->setFunction(function() use(&$specs, &$callCount, &$isExecuted) {
			$callCount++;
			
			$isExecuted[$callCount][] = 1;
			$assert = new \spectrum\core\Assertion($specs[0]->getRunningDescendantEndingSpec(), "aaa");
			$assert->failMatcher();
			$isExecuted[$callCount][] = 2;
		});
		
		$specs[1]->getErrorHandling()->setBreakOnFirstMatcherFail(true);
		$specs[2]->getErrorHandling()->setBreakOnFirstMatcherFail(false);
		$specs[3]->getErrorHandling()->setBreakOnFirstMatcherFail(true);
		$specs[4]->getErrorHandling()->setBreakOnFirstMatcherFail(false);
		
		$specs[0]->run();
		
		$this->assertSame(array(
			array(1),
			array(1, 2),
			array(1),
			array(1, 2),
		), $isExecuted);
	}
	
	public function testMatcherCall_MatcherReturnsTrue_AddsTrueWithMatcherCallDetailsToResults() {
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return true; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results, &$line, &$file) {
			$results = $spec->getResults();
		
			$assert = new Assertion($spec, "aaa bbb");
			$assert->zzz("ccc", "ddd", "eee"); $line = __LINE__;
			$file = __FILE__;
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(1, count($resultsContent));
		
		$this->assertSame(true, $resultsContent[0]['result']);
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $resultsContent[0]['details']);
		$this->assertSame('aaa bbb', $resultsContent[0]['details']->getTestedValue());
		$this->assertSame(false, $resultsContent[0]['details']->getNot());
		$this->assertSame(true, $resultsContent[0]['details']->getResult());
		$this->assertSame('zzz', $resultsContent[0]['details']->getMatcherName());
		$this->assertSame(array('ccc', 'ddd', 'eee'), $resultsContent[0]['details']->getMatcherArguments());
		$this->assertSame(true, $resultsContent[0]['details']->getMatcherReturnValue());
		$this->assertSame(null, $resultsContent[0]['details']->getMatcherException());
		$this->assertSame($file, $resultsContent[0]['details']->getFile());
		$this->assertSame($line, $resultsContent[0]['details']->getLine());
	}
	
	public function testMatcherCall_MatcherReturnsTrue_CastsResultToTrue() {
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return 1; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results) {
			$results = $spec->getResults();
		
			$assert = new Assertion($spec, "aaa bbb");
			$assert->zzz();
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(1, count($resultsContent));
		$this->assertSame(true, $resultsContent[0]['result']);
		$this->assertSame(true, $resultsContent[0]['details']->getResult());
		$this->assertSame(1, $resultsContent[0]['details']->getMatcherReturnValue());
	}
	
	public function testMatcherCall_MatcherReturnsTrue_DoesNotBreakExecution() {
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return true; });
		$spec->getExecutor()->setFunction(function() use($spec, &$isExecuted) {
			$assert = new Assertion($spec, "aaa bbb");
			$assert->zzz();
			$isExecuted = true;
		});
		$spec->run();
		
		$this->assertSame(true, $isExecuted);
	}
	
	public function testMatcherCall_MatcherReturnsTrue_NotFlagIsEnabled_AddsFalseWithMatcherCallDetailsToResults() {
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return true; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results, &$line, &$file) {
			$results = $spec->getResults();
		
			$assert = new Assertion($spec, "aaa bbb");
			$assert->not->zzz("ccc", "ddd", "eee"); $line = __LINE__;
			$file = __FILE__;
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(1, count($resultsContent));
		
		$this->assertSame(false, $resultsContent[0]['result']);
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $resultsContent[0]['details']);
		$this->assertSame('aaa bbb', $resultsContent[0]['details']->getTestedValue());
		$this->assertSame(true, $resultsContent[0]['details']->getNot());
		$this->assertSame(false, $resultsContent[0]['details']->getResult());
		$this->assertSame('zzz', $resultsContent[0]['details']->getMatcherName());
		$this->assertSame(array('ccc', 'ddd', 'eee'), $resultsContent[0]['details']->getMatcherArguments());
		$this->assertSame(true, $resultsContent[0]['details']->getMatcherReturnValue());
		$this->assertSame(null, $resultsContent[0]['details']->getMatcherException());
		$this->assertSame($file, $resultsContent[0]['details']->getFile());
		$this->assertSame($line, $resultsContent[0]['details']->getLine());
	}
	
	public function testMatcherCall_MatcherReturnsTrue_BreakOnFirstMatcherFailIsTrue_DoesNotBreakExecution() {
		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstMatcherFail(true);
		$spec->getMatchers()->add('zzz', function(){ return true; });
		$spec->getExecutor()->setFunction(function() use($spec, &$isExecuted) {
			$assert = new Assertion($spec, "aaa");
			$assert->zzz();
			$isExecuted = true;
		});
		$spec->run();
		$this->assertSame(true, $isExecuted);
	}
	
	public function testMatcherCall_MatcherThrowsException_AddsFalseWithMatcherCallDetailsToResults() {
		$spec = new Spec();
		$exception = new \Exception('Something wrong');
		$spec->getMatchers()->add('zzz', function() use($exception){ throw $exception; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results, &$line, &$file) {
			$results = $spec->getResults();
		
			$assert = new Assertion($spec, "aaa bbb");
			$assert->zzz("ccc", "ddd", "eee"); $line = __LINE__;
			$file = __FILE__;
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(1, count($resultsContent));
		
		$this->assertSame(false, $resultsContent[0]['result']);
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $resultsContent[0]['details']);
		$this->assertSame('aaa bbb', $resultsContent[0]['details']->getTestedValue());
		$this->assertSame(false, $resultsContent[0]['details']->getNot());
		$this->assertSame(false, $resultsContent[0]['details']->getResult());
		$this->assertSame('zzz', $resultsContent[0]['details']->getMatcherName());
		$this->assertSame(array('ccc', 'ddd', 'eee'), $resultsContent[0]['details']->getMatcherArguments());
		$this->assertSame(null, $resultsContent[0]['details']->getMatcherReturnValue());
		$this->assertSame($exception, $resultsContent[0]['details']->getMatcherException());
		$this->assertSame('Something wrong', $resultsContent[0]['details']->getMatcherException()->getMessage());
		$this->assertSame($file, $resultsContent[0]['details']->getFile());
		$this->assertSame($line, $resultsContent[0]['details']->getLine());
	}
	
	public function testMatcherCall_MatcherThrowsException_DoesNotBreakExecution() {
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ throw new \Exception(); });
		$spec->getExecutor()->setFunction(function() use($spec, &$isExecuted) {
			$assert = new Assertion($spec, "aaa bbb");
			$assert->zzz();
			$isExecuted = true;
		});
		$spec->run();
		
		$this->assertSame(true, $isExecuted);
	}
	
	public function testMatcherCall_MatcherThrowsException_NotFlagIsEnabled_DoesNotInvertResultAndAddsFalseWithMatcherCallDetailsToResults() {
		$spec = new Spec();
		$exception = new \Exception('Something wrong');
		$spec->getMatchers()->add('zzz', function() use($exception){ throw $exception; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results, &$line, &$file) {
			$results = $spec->getResults();
		
			$assert = new Assertion($spec, "aaa bbb");
			$assert->not->zzz("ccc", "ddd", "eee"); $line = __LINE__;
			$file = __FILE__;
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(1, count($resultsContent));
		
		$this->assertSame(false, $resultsContent[0]['result']);
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $resultsContent[0]['details']);
		$this->assertSame('aaa bbb', $resultsContent[0]['details']->getTestedValue());
		$this->assertSame(true, $resultsContent[0]['details']->getNot());
		$this->assertSame(false, $resultsContent[0]['details']->getResult());
		$this->assertSame('zzz', $resultsContent[0]['details']->getMatcherName());
		$this->assertSame(array('ccc', 'ddd', 'eee'), $resultsContent[0]['details']->getMatcherArguments());
		$this->assertSame(null, $resultsContent[0]['details']->getMatcherReturnValue());
		$this->assertSame($exception, $resultsContent[0]['details']->getMatcherException());
		$this->assertSame('Something wrong', $resultsContent[0]['details']->getMatcherException()->getMessage());
		$this->assertSame($file, $resultsContent[0]['details']->getFile());
		$this->assertSame($line, $resultsContent[0]['details']->getLine());
	}
	
	public function testMatcherCall_MatcherThrowsException_BreakOnFirstMatcherFailIsTrue_BreaksExecution() {
		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstMatcherFail(true);
		$spec->getMatchers()->add('zzz', function(){ throw new \Exception(); });
		$spec->getExecutor()->setFunction(function() use($spec, &$isExecuted) {
			$assert = new Assertion($spec, "aaa");
			$assert->zzz();
			$isExecuted = true;
		});
		$spec->run();
		$this->assertSame(null, $isExecuted);
	}
	
	public function testMatcherCall_MatcherNotExists_AddsFalseResultToResults() {
		$spec = new Spec();
		$spec->getMatchers()->remove('zzz');
		$spec->getExecutor()->setFunction(function() use($spec, &$results) {
			$results = $spec->getResults();
			$assert = new Assertion($spec, "aaa");
			$assert->zzz();
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(1, count($resultsContent));
		$this->assertSame(false, $resultsContent[0]['result']);
		$this->assertInstanceOf('\spectrum\Exception', $resultsContent[0]['details']);
		$this->assertSame('Matcher "zzz" not exists', $resultsContent[0]['details']->getMessage());
	}
	
	public function testMatcherCall_MatcherNotExists_DoesNotBreakExecution() {
		$spec = new Spec();
		$spec->getMatchers()->remove('zzz');
		$spec->getExecutor()->setFunction(function() use($spec, &$isExecuted) {
			$assert = new Assertion($spec, "aaa");
			$assert->zzz();
			$isExecuted = true;
		});
		$spec->run();
		
		$this->assertSame(true, $isExecuted);
	}
	
	public function testMatcherCall_MatcherNotExists_ReturnsAssertionInstance() {
		$spec = new Spec();
		$spec->getMatchers()->remove('zzz');
		$spec->getExecutor()->setFunction(function() use($spec, &$assertion, &$returnValue) {
			$assertion = new Assertion($spec, "aaa");
			$returnValue = $assertion->zzz();
		});
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Assertion', $returnValue);
		$this->assertSame($assertion, $returnValue);
	}

	public function testMatcherCall_CallOnNotRun_ThrowsExceptionAndDoesNotCallMatcher() {
		$spec = new Spec();
		$spec->setName('aaa');
		
		$isCalled = false;
		$spec->getMatchers()->add('zzz', function() use(&$isCalled) {
			$isCalled = true;
		});
		
		$assert = new Assertion($spec, null);
		$this->assertThrowsException('\spectrum\Exception', 'Matcher call is denied on not running spec (now spec "aaa" is not running)', function() use($assert){
			$assert->zzz();
		});
		
		$this->assertSame(false, $isCalled);
	}
	
	public function testEventDispatch_OnMatcherCallStart_IsDispatchedBeforeMatcherCall() {
		$calls = array();
		config::registerEventListener('onMatcherCallStart', function() use(&$calls) {
			$calls[] = "event";
		});
		
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function() use(&$calls) { $calls[] = "matcher"; });
		$spec->getExecutor()->setFunction(function() use(&$spec) {
			$assert = new Assertion($spec, "aaa");
			$assert->zzz();
		});
		$spec->run();
		
		$this->assertSame(array('event', 'matcher'), $calls);
	}
	
	public function testEventDispatch_OnMatcherCallStart_PassesMatcherCallDetailsToCalleeMethod() {
		config::registerEventListener('onMatcherCallStart', function(SpecInterface $spec, AssertionInterface $assertion, MatcherCall $matcherCallDetails2) use(&$matcherCallDetails) {
			$matcherCallDetails = $matcherCallDetails2;
		});
		
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return 'rrr'; });
		$spec->getExecutor()->setFunction(function() use($spec, &$line, &$file) {
			$assert = new Assertion($spec, "aaa");
			$assert->zzz("bbb", "ccc", "ddd"); $line = __LINE__;
			$file = __FILE__;
		});
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $matcherCallDetails);
		$this->assertSame('aaa', $matcherCallDetails->getTestedValue());
		$this->assertSame(false, $matcherCallDetails->getNot());
		$this->assertSame(true, $matcherCallDetails->getResult());
		$this->assertSame('zzz', $matcherCallDetails->getMatcherName());
		$this->assertSame(array('bbb', 'ccc', 'ddd'), $matcherCallDetails->getMatcherArguments());
		$this->assertSame('rrr', $matcherCallDetails->getMatcherReturnValue());
		$this->assertSame(null, $matcherCallDetails->getMatcherException());
		$this->assertSame($file, $matcherCallDetails->getFile());
		$this->assertSame($line, $matcherCallDetails->getLine());
	}
	
	public function testEventDispatch_OnMatcherCallFinish_IsDispatchedAfterMatcherCall() {
		$calls = array();
		config::registerEventListener('onMatcherCallFinish', function() use(&$calls) {
			$calls[] = "event";
		});
		
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function() use(&$calls) { $calls[] = "matcher"; });
		$spec->getExecutor()->setFunction(function() use(&$spec) {
			$assert = new Assertion($spec, "aaa");
			$assert->zzz();
		});
		$spec->run();
		
		$this->assertSame(array('matcher', 'event'), $calls);
	}
	
	public function testEventDispatch_OnMatcherCallFinish_IsDispatchedAfterResultAddToResults() {
		config::registerEventListener('onMatcherCallFinish', function(SpecInterface $spec) use(&$results) {
			$results = $spec->getResults()->getAll();
		});
		
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function() { return false; });
		$spec->getExecutor()->setFunction(function() use(&$spec) {
			$assert = new Assertion($spec, "aaa");
			$assert->zzz();
		});
		$spec->run();
		
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $results[0]['details']);
		$this->assertSame('zzz', $results[0]['details']->getMatcherName());
	}
	
	public function testEventDispatch_OnMatcherCallFinish_IsDispatchedAfterNotFlagReset() {
		config::registerEventListener('onMatcherCallFinish', function(SpecInterface $spec, AssertionInterface $assertion) use(&$isCalled, &$assertion) {
			if (!$isCalled) {
				$isCalled = true;
				$assertion->zzz();
			}
		});

		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return true; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results, &$assertion) {
			$results = $spec->getResults();
		
			$assertion = new Assertion($spec, null);
			$assertion->not->zzz();
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(2, count($resultsContent));
		$this->assertSame(false, $resultsContent[0]['result']);
		$this->assertSame(true, $resultsContent[1]['result']);
	}
	
	public function testEventDispatch_OnMatcherCallFinish_IsDispatchedBeforeExecutionBreak() {
		config::registerEventListener('onMatcherCallFinish', function() use(&$isCalled) {
			$isCalled = true;
		});

		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstMatcherFail(true);
		$spec->getMatchers()->add('zzz', function(){ return false; });
		$spec->getExecutor()->setFunction(function() use(&$spec) {
			$assertion = new Assertion($spec, null);
			$assertion->zzz();
		});
		$spec->run();
		
		$this->assertSame(true, $isCalled);
	}
	
	public function testEventDispatch_OnMatcherCallFinish_PassesMatcherCallDetailsToEventListeners() {
		config::registerEventListener('onMatcherCallFinish', function(SpecInterface $spec, AssertionInterface $assertion, MatcherCall $matcherCallDetails2) use(&$matcherCallDetails) {
			$matcherCallDetails = $matcherCallDetails2;
		});
		
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return 'rrr'; });
		$spec->getExecutor()->setFunction(function() use($spec, &$line, &$file) {
			$assert = new Assertion($spec, "aaa");
			$assert->zzz("bbb", "ccc", "ddd"); $line = __LINE__;
			$file = __FILE__;
		});
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\details\MatcherCall', $matcherCallDetails);
		$this->assertSame('aaa', $matcherCallDetails->getTestedValue());
		$this->assertSame(false, $matcherCallDetails->getNot());
		$this->assertSame(true, $matcherCallDetails->getResult());
		$this->assertSame('zzz', $matcherCallDetails->getMatcherName());
		$this->assertSame(array('bbb', 'ccc', 'ddd'), $matcherCallDetails->getMatcherArguments());
		$this->assertSame('rrr', $matcherCallDetails->getMatcherReturnValue());
		$this->assertSame(null, $matcherCallDetails->getMatcherException());
		$this->assertSame($file, $matcherCallDetails->getFile());
		$this->assertSame($line, $matcherCallDetails->getLine());
	}
	
/**/
	
	public function testPropertyAccess_PropertyNotExists_ThrowsException() {
		$assert = new Assertion(new Spec(), null);
		$this->assertThrowsException('\spectrum\Exception', 'Undefined property "aaa" in "\spectrum\core\Assertion" class', function() use($assert){
			$assert->aaa;
		});
	}
		
	public function testPropertyAccess_Not_InvertsNotFlag() {
		$spec = new Spec();
		$spec->getMatchers()->add('zzz', function(){ return true; });
		$spec->getExecutor()->setFunction(function() use($spec, &$results) {
			$results = $spec->getResults();
		
			$assert = new Assertion($spec, null);
			$assert->not->zzz();
		});
		$spec->run();
		
		$resultsContent = $results->getAll();
		$this->assertSame(1, count($resultsContent));
		$this->assertSame(false, $resultsContent[0]['result']);
	}
	
	public function testPropertyAccess_Not_ReturnsAssertionInstance() {
		$spec = new Spec();
		$spec->getExecutor()->setFunction(function() use($spec, &$assertion, &$returnValue) {
			$assertion = new Assertion($spec, "aaa");
			$returnValue = $assertion->not;
		});
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Assertion', $returnValue);
		$this->assertSame($assertion, $returnValue);
	}
}