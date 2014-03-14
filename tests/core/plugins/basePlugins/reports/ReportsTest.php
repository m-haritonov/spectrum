<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\core\plugins\basePlugins\reports;
use spectrum\config;
use spectrum\core\Assert;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../../init.php';

class ReportsTest extends \spectrum\tests\Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->restoreClassStaticProperties('\spectrum\config');
	}
	
	public function testOutputFormatIsHtml_AllowsOutputDataBuffering()
	{
		ob_start();
		
		config::setOutputFormat('html');
		$spec = new Spec();
		$spec->test->setFunction(function(){ throw new \Exception('<>&"\''); });
		$spec->run();
		
		$html = ob_get_contents();
		ob_end_clean();
		
		$this->assertNotEquals('', $html);
		$this->assertContains('<html', $html);
		$this->assertContains('<body', $html);
		$this->assertContains('</body>', $html);
		$this->assertContains('</html>', $html);
	}
	
	public function testOutputFormatIsHtml_GeneratesValidXhtml1StrictCode()
	{
		ob_start();
		config::setOutputFormat('html');
		config::setOutputIndention("\t");
		config::setOutputNewline("\r\n");
		
		$groupSpec = new Spec();
		
		// Tests for generating data by "test" plugin
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->matchers->add('<>&"\'', function(){ throw new \Exception('<>&"\''); });
		$spec->test->setFunction(function() use($spec){
			$assert = new Assert($spec, null);
			$assert->__call('<>&"\'');
		});
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->matchers->add('<>&"\'', function(){ return '<>&"\''; });
		$spec->test->setFunction(function() use($spec){
			$object = new \stdClass();
			$object->{'<>&"\''} = '<>&"\'';
			$object->aaa = array('<>&"\'' => '<>&"\'');
			
			$assert = new Assert($spec, '<>&"\'');
			$assert->__call('<>&"\'', array(
				'<>&"\'',
				array('<>&"\'' => '<>&"\''),
				$object,
			));
		});
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->test->setFunction(function(){ throw new \Exception('<>&"\''); });
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->errorHandling->setCatchPhpErrors(true);
		$spec->test->setFunction(function(){ trigger_error('<>&"\''); });
		
		// Tests for generating data by context modifiers with "before" type
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->test->setFunction(function(){});
		$spec->matchers->add('<>&"\'', function(){ throw new \Exception('<>&"\''); });
		$spec->contexts->add(function() use($spec){
			$assert = new Assert($spec, null);
			$assert->__call('<>&"\'');
		}, 'before');
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->test->setFunction(function(){});
		$spec->matchers->add('<>&"\'', function(){ return '<>&"\''; });
		$spec->contexts->add(function() use($spec){
			$object = new \stdClass();
			$object->{'<>&"\''} = '<>&"\'';
			$object->aaa = array('<>&"\'' => '<>&"\'');
			
			$assert = new Assert($spec, '<>&"\'');
			$assert->__call('<>&"\'', array(
				'<>&"\'',
				array('<>&"\'' => '<>&"\''),
				$object,
			));
		}, 'before');
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->test->setFunction(function(){});
		$spec->contexts->add(function(){ throw new \Exception('<>&"\''); }, 'before');
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->test->setFunction(function(){});
		$spec->errorHandling->setCatchPhpErrors(true);
		$spec->contexts->add(function(){ trigger_error('<>&"\''); }, 'before');
		
		// Tests for generating data by context modifiers with "after" type
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->test->setFunction(function(){});
		$spec->matchers->add('<>&"\'', function(){ throw new \Exception('<>&"\''); });
		$spec->contexts->add(function() use($spec){
			$assert = new Assert($spec, null);
			$assert->__call('<>&"\'');
		}, 'after');
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->test->setFunction(function(){});
		$spec->matchers->add('<>&"\'', function(){ return '<>&"\''; });
		$spec->contexts->add(function() use($spec){
			$object = new \stdClass();
			$object->{'<>&"\''} = '<>&"\'';
			$object->aaa = array('<>&"\'' => '<>&"\'');
			
			$assert = new Assert($spec, '<>&"\'');
			$assert->__call('<>&"\'', array(
				'<>&"\'',
				array('<>&"\'' => '<>&"\''),
				$object,
			));
		}, 'after');
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->test->setFunction(function(){});
		$spec->contexts->add(function(){ throw new \Exception('<>&"\''); }, 'after');
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->test->setFunction(function(){});
		$spec->errorHandling->setCatchPhpErrors(true);
		$spec->contexts->add(function(){ trigger_error('<>&"\''); }, 'after');
		
		// Tests for generating data by "\spectrum\core\details\*" classes
		
		$spec = new Spec();
		$spec->bindParentSpec($groupSpec);
		$spec->test->setFunction(function() use($spec){
			$details = new \spectrum\core\details\MatcherCall();
			$details->setTestedValue('<>&"\'');
			$details->setNot('<>&"\'');
			$details->setResult('<>&"\'');
			$details->setMatcherName('<>&"\'');
			$details->setMatcherArguments(array('<>&"\'', '<>&"\'', '<>&"\''));
			$details->setMatcherReturnValue('<>&"\'');
			$details->setMatcherException('<>&"\'');
			$details->setFile('<>&"\'');
			$details->setLine('<>&"\'');
			$spec->getResultBuffer()->addResult(false, $details);
			
			$spec->getResultBuffer()->addResult(false, new \spectrum\core\details\PhpError('<>&"\'', '<>&"\'', '<>&"\'', '<>&"\''));
			$spec->getResultBuffer()->addResult(false, new \spectrum\core\details\UserFail('<>&"\''));
		});
		
		// Tests for "id" attribute uniqueness
		
		$spec1 = new Spec();
		$spec1->bindParentSpec($groupSpec);
		
		$spec2 = new Spec();
		$spec2->bindParentSpec($groupSpec);
		
		$spec3 = new Spec();
		$spec3->bindParentSpec($spec1);
		$spec3->bindParentSpec($spec2);
		$spec3->test->setFunction(function(){});
		
		//
		
		$groupSpec->run();
		
		$html = ob_get_contents();
		ob_end_clean();
		
		libxml_clear_errors();
		$domDocument = new \DOMDocument();
		
		$this->assertNotEquals('', $html);
		$this->assertTrue($domDocument->loadHTML($html));
		$this->assertTrue($domDocument->loadXML($html));
		$this->assertTrue($domDocument->schemaValidate(__DIR__ . '/../../../../_testware/xhtml1-strict.xsd'));
		$this->assertSame(array(), libxml_get_errors());
	}
	
	public function testOutputFormatIsNotSupported_ThrowsException()
	{
		config::setOutputFormat('aaa');
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Output format "aaa" is not supported by "Reports" plugin', function() use($spec){
			$spec->run();
		});
	}
}