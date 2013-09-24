<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins\basePlugins;
use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../init.php';

class OutputTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::setAllowInputEncodingModify(true);
		config::setAllowOutputEncodingModify(true);
	}
	
	public function testSetInputEncoding_SetsNewEncoding()
	{
		$spec = new Spec();
		$spec->output->setInputEncoding('windows-1251');
		$this->assertSame('windows-1251', $spec->output->getInputEncoding());
		
		$spec->output->setInputEncoding('koi8-r');
		$this->assertSame('koi8-r', $spec->output->getInputEncoding());
	}
	
	public function testSetInputEncoding_CallOnRun_ThrowsExceptionAndDoesNotChangeEncoding()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->output->setInputEncoding("koi8-r");
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		

		$spec = new Spec();
		$spec->output->setInputEncoding('windows-1251');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Modify spec plugins when spec tree is running deny', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame('windows-1251', $spec->output->getInputEncoding());
	}
	
	public function testSetInputEncoding_InputEncodingModifyDenyInConfig_ThrowsExceptionAndDoesNotChangeEncoding()
	{
		$spec = new Spec();
		$spec->output->setInputEncoding('windows-1251');
		
		config::setAllowInputEncodingModify(false);
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Input encoding modify deny in config', function() use($spec){
			$spec->output->setInputEncoding('koi8-r');
		});
		
		$this->assertSame('windows-1251', $spec->output->getInputEncoding());
	}
	
/**/
	
	public function testGetInputEncoding_ReturnsSetEncoding()
	{
		$spec = new Spec();
		$spec->output->setInputEncoding('windows-1251');
		$this->assertSame('windows-1251', $spec->output->getInputEncoding());
		
		$spec->output->setInputEncoding('koi8-r');
		$this->assertSame('koi8-r', $spec->output->getInputEncoding());
	}
	
	public function testGetInputEncoding_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->output->getInputEncoding());
	}

/**/
	
	public function testGetInputEncodingThroughRunningAncestors_ReturnsEncodingFromRunningAncestorOrFromSelf()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->output->getInputEncodingThroughRunningAncestors();
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->Spec(parent3)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2', 'parent2' => 'endingSpec2'));
		
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$specs[0]->output->setInputEncoding('windows-1251', $function1);
		$specs['endingSpec1']->output->setInputEncoding('windows-1252', $function2);
		$specs['parent1']->output->setInputEncoding('windows-1253', $function3);
		$specs['parent2']->output->setInputEncoding('windows-1254', $function4);
		
		$specs[0]->run();
		
		$this->assertSame(array('windows-1252', 'windows-1253', 'windows-1254', 'windows-1251'), \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testGetInputEncodingThroughRunningAncestors_ReturnsUtf8ByDefault()
	{
		$spec = new Spec();
		$this->assertSame('utf-8', $spec->output->getInputEncodingThroughRunningAncestors());
	}
	
/**/

	public function testSetOutputEncoding_SetsNewEncoding()
	{
		$spec = new Spec();
		$spec->output->setOutputEncoding('windows-1251');
		$this->assertSame('windows-1251', $spec->output->getOutputEncoding());
		
		$spec->output->setOutputEncoding('koi8-r');
		$this->assertSame('koi8-r', $spec->output->getOutputEncoding());
	}
	
	public function testSetOutputEncoding_CallOnRun_ThrowsExceptionAndDoesNotChangeEncoding()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->output->setOutputEncoding("koi8-r");
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		

		$spec = new Spec();
		$spec->output->setOutputEncoding('windows-1251');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Modify spec plugins when spec tree is running deny', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame('windows-1251', $spec->output->getOutputEncoding());
	}
	
	public function testSetOutputEncoding_OutputEncodingModifyDenyInConfig_ThrowsExceptionAndDoesNotChangeEncoding()
	{
		$spec = new Spec();
		$spec->output->setOutputEncoding('windows-1251');
		
		config::setAllowOutputEncodingModify(false);
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Output encoding modify deny in config', function() use($spec){
			$spec->output->setOutputEncoding('koi8-r');
		});
		
		$this->assertSame('windows-1251', $spec->output->getOutputEncoding());
	}
	
/**/
	
	public function testGetOutputEncoding_ReturnsSetEncoding()
	{
		$spec = new Spec();
		$spec->output->setOutputEncoding('windows-1251');
		$this->assertSame('windows-1251', $spec->output->getOutputEncoding());
		
		$spec->output->setOutputEncoding('koi8-r');
		$this->assertSame('koi8-r', $spec->output->getOutputEncoding());
	}
	
	public function testGetOutputEncoding_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->output->getOutputEncoding());
	}

/**/
	
	public function testGetOutputEncodingThroughRunningAncestors_ReturnsEncodingFromRunningAncestorOrFromSelf()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->output->getOutputEncodingThroughRunningAncestors();
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->Spec(parent3)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2', 'parent2' => 'endingSpec2'));
		
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$specs[0]->output->setOutputEncoding('windows-1251', $function1);
		$specs['endingSpec1']->output->setOutputEncoding('windows-1252', $function2);
		$specs['parent1']->output->setOutputEncoding('windows-1253', $function3);
		$specs['parent2']->output->setOutputEncoding('windows-1254', $function4);
		
		$specs[0]->run();
		
		$this->assertSame(array('windows-1252', 'windows-1253', 'windows-1254', 'windows-1251'), \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testGetOutputEncodingThroughRunningAncestors_ReturnsUtf8ByDefault()
	{
		$spec = new Spec();
		$this->assertSame('utf-8', $spec->output->getOutputEncodingThroughRunningAncestors());
	}

/**/
	
	public function testPut_PrintsStringInCorrectEncoding()
	{
		$spec = new Spec();
		$spec->output->setInputEncoding('utf-8');
		$spec->output->setOutputEncoding('windows-1251');
		
		ob_start();
		$spec->output->put($this->toUtf8('тестовая строка'));
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertSame($this->toWindows1251('тестовая строка'), $output);
	}
	
/**/
	
	public function dataProviderConvertToOutputEncoding()
	{
		return array(
			array('utf-8',        'utf-8',        $this->toUtf8('тестовая строка'),        $this->toUtf8('тестовая строка')),
			array('utf-8',        'windows-1251', $this->toUtf8('тестовая строка'),        $this->toWindows1251('тестовая строка')),
			array('windows-1251', 'utf-8',        $this->toWindows1251('тестовая строка'), $this->toUtf8('тестовая строка')),
			array('windows-1251', 'windows-1251', $this->toWindows1251('тестовая строка'), $this->toWindows1251('тестовая строка')),
		);
	}

	/**
	 * @dataProvider dataProviderConvertToOutputEncoding
	 */
	public function testConvertToOutputEncoding_ReturnsStringConvertedFromInputEncodingToOutputEncoding($inputEncoding, $outputEncoding, $actualString, $expectedString)
	{
		$spec = new Spec();
		$spec->output->setInputEncoding($inputEncoding);
		$spec->output->setOutputEncoding($outputEncoding);
		$this->assertSame($expectedString, $spec->output->convertToOutputEncoding($actualString));
	}
	
	public function testConvertToOutputEncoding_IgnoresEncodingNameCase()
	{
		$spec = new Spec();
		$spec->output->setInputEncoding('WINDOWS-1251');
		$spec->output->setOutputEncoding('windows-1251');
		$this->assertSame($this->toWindows1251('тестовая строка'), $spec->output->convertToOutputEncoding($this->toWindows1251('тестовая строка')));
	}

	public function testConvertToOutputEncoding_UsesUtf8AsDefaultInputEncodingAndDefaultOutputEncoding()
	{
		$spec = new Spec();
		$this->assertSame($this->toUtf8('тестовая строка'), $spec->output->convertToOutputEncoding($this->toUtf8('тестовая строка')));
	}

	public function testConvertToOutputEncoding_GetsInputEncodingFromRunningAncestorOrFromSelf()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			$runningParentSpec = $this->getOwnerSpec()->getRunningParentSpec();
			
			if ($runningParentSpec === \spectrum\tests\Test::$temp["specs"]["parent3"])
				$string = "' . iconv("utf-8", "utf-8", "тестовая строка") . '";
			else if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"]["endingSpec1"])
				$string = "' . iconv("utf-8", "windows-1251", "тестовая строка") . '";
			else if ($runningParentSpec === \spectrum\tests\Test::$temp["specs"]["parent1"])
				$string = "' . iconv("utf-8", "koi8-r", "тестовая строка") . '";
			else if ($runningParentSpec === \spectrum\tests\Test::$temp["specs"]["parent2"])
				$string = "' . iconv("utf-8", "iso-8859-5", "тестовая строка") . '";
			else
				$string = null;
				
			\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->output->convertToOutputEncoding($string);
		', 'onEndingSpecExecute');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->Spec(parent3)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2', 'parent2' => 'endingSpec2'));
		
		\spectrum\tests\Test::$temp["specs"][0]->output->setOutputEncoding('utf-8');
		
		\spectrum\tests\Test::$temp["specs"][0]->output->setInputEncoding('utf-8');
		\spectrum\tests\Test::$temp["specs"]['endingSpec1']->output->setInputEncoding('windows-1251');
		\spectrum\tests\Test::$temp["specs"]['parent1']->output->setInputEncoding('koi8-r');
		\spectrum\tests\Test::$temp["specs"]['parent2']->output->setInputEncoding('iso-8859-5');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		
		$this->assertSame(array(
			$this->toUtf8('тестовая строка'),
			$this->toUtf8('тестовая строка'),
			$this->toUtf8('тестовая строка'),
			$this->toUtf8('тестовая строка'),
		), \spectrum\tests\Test::$temp["returnValues"]);
	}

	public function testConvertToOutputEncoding_GetsOutputEncodingFromRunningAncestorOrFromSelf()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->output->convertToOutputEncoding("' . $this->toUtf8('тестовая строка') . '");
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->Spec(parent3)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2', 'parent2' => 'endingSpec2'));
		
		$specs[0]->output->setInputEncoding('utf-8');
		
		$specs[0]->output->setOutputEncoding('utf-8');
		$specs['endingSpec1']->output->setOutputEncoding('windows-1251');
		$specs['parent1']->output->setOutputEncoding('koi8-r');
		$specs['parent2']->output->setOutputEncoding('iso-8859-5');
		
		$specs[0]->run();
		
		$this->assertSame(array(
			iconv('utf-8', 'windows-1251', 'тестовая строка'),
			iconv('utf-8', 'koi8-r', 'тестовая строка'),
			iconv('utf-8', 'iso-8859-5', 'тестовая строка'),
			iconv('utf-8', 'utf-8', 'тестовая строка'),
		), \spectrum\tests\Test::$temp["returnValues"]);
	}

/**/

	private function toWindows1251($string)
	{
		return iconv('utf-8', 'windows-1251', $string);
	}

	private function toUtf8($string)
	{
		// Conversion not necessary, because this file in utf-8
		return $string;
	}
}