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
		config::setAllowInputCharsetModify(true);
	}
	
	public function testPut_InputCharsetArgumentIsNull_PrintsStringInCorrectCharset()
	{
		config::setOutputCharset('windows-1251');
		$spec = new Spec();
		$spec->charset->setInputCharset('utf-8');
		
		ob_start();
		$spec->output->put($this->toUtf8('тестовая строка'));
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertSame($this->toWindows1251('тестовая строка'), $output);
	}
	
	public function testPut_InputCharsetArgumentIsSet_PrintsStringInCorrectCharset()
	{
		config::setOutputCharset('windows-1251');
		$spec = new Spec();
		$spec->charset->setInputCharset('koi8-r');
		
		ob_start();
		$spec->output->put($this->toUtf8('тестовая строка'), 'utf-8');
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertSame($this->toWindows1251('тестовая строка'), $output);
	}
	
/**/
	
	public function dataProviderConvertToOutputCharset()
	{
		return array(
			array('utf-8',        'utf-8',        $this->toUtf8('тестовая строка'),        $this->toUtf8('тестовая строка')),
			array('utf-8',        'windows-1251', $this->toUtf8('тестовая строка'),        $this->toWindows1251('тестовая строка')),
			array('windows-1251', 'utf-8',        $this->toWindows1251('тестовая строка'), $this->toUtf8('тестовая строка')),
			array('windows-1251', 'windows-1251', $this->toWindows1251('тестовая строка'), $this->toWindows1251('тестовая строка')),
		);
	}

	/**
	 * @dataProvider dataProviderConvertToOutputCharset
	 */
	public function testConvertToOutputCharset_InputCharsetArgumentIsNull_ReturnsStringConvertedFromInputCharsetToOutputCharset($inputCharset, $outputCharset, $actualString, $expectedString)
	{
		config::setOutputCharset($outputCharset);
		$spec = new Spec();
		$spec->charset->setInputCharset($inputCharset);
		$this->assertSame($expectedString, $spec->output->convertToOutputCharset($actualString));
	}
	
	public function testConvertToOutputCharset_InputCharsetArgumentIsNull_IgnoresCharsetNameCase()
	{
		config::setOutputCharset('windows-1251');
		$spec = new Spec();
		$spec->charset->setInputCharset('WINDOWS-1251');
		$this->assertSame($this->toWindows1251('тестовая строка'), $spec->output->convertToOutputCharset($this->toWindows1251('тестовая строка')));
	}

	public function testConvertToOutputCharset_InputCharsetArgumentIsNull_UsesUtf8AsDefaultInputCharset()
	{
		config::setOutputCharset('utf-8');
		$spec = new Spec();
		$this->assertSame($this->toUtf8('тестовая строка'), $spec->output->convertToOutputCharset($this->toUtf8('тестовая строка')));
	}

	public function testConvertToOutputCharset_InputCharsetArgumentIsNull_GetsInputCharsetFromRunningAncestorOrFromSelf()
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
				
			\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->output->convertToOutputCharset($string);
		', 'onEndingSpecExecute');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->Spec(parent3)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2', 'parent2' => 'endingSpec2'));
		
		config::setOutputCharset('utf-8');
		
		\spectrum\tests\Test::$temp["specs"][0]->charset->setInputCharset('utf-8');
		\spectrum\tests\Test::$temp["specs"]['endingSpec1']->charset->setInputCharset('windows-1251');
		\spectrum\tests\Test::$temp["specs"]['parent1']->charset->setInputCharset('koi8-r');
		\spectrum\tests\Test::$temp["specs"]['parent2']->charset->setInputCharset('iso-8859-5');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		
		$this->assertSame(array(
			$this->toUtf8('тестовая строка'),
			$this->toUtf8('тестовая строка'),
			$this->toUtf8('тестовая строка'),
			$this->toUtf8('тестовая строка'),
		), \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	/**
	 * @dataProvider dataProviderConvertToOutputCharset
	 */
	public function testConvertToOutputCharset_InputCharsetArgumentIsSet_ReturnsStringConvertedFromPassedInputCharsetToOutputCharset($inputCharset, $outputCharset, $actualString, $expectedString)
	{
		config::setOutputCharset($outputCharset);
		$spec = new Spec();
		$spec->charset->setInputCharset('koi8-r');
		$this->assertSame($expectedString, $spec->output->convertToOutputCharset($actualString, $inputCharset));
	}
	
	public function testConvertToOutputCharset_InputCharsetArgumentIsSet_IgnoresCharsetNameCase()
	{
		config::setOutputCharset('windows-1251');
		$spec = new Spec();
		$spec->charset->setInputCharset('koi8-r');
		$this->assertSame($this->toWindows1251('тестовая строка'), $spec->output->convertToOutputCharset($this->toWindows1251('тестовая строка'), 'WINDOWS-1251'));
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