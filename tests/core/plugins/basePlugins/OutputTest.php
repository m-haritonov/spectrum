<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
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
		$spec->setInputCharset('utf-8');
		
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
		$spec->setInputCharset('koi8-r');
		
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
		$spec->setInputCharset($inputCharset);
		$this->assertSame($expectedString, $spec->output->convertToOutputCharset($actualString));
	}
	
	public function testConvertToOutputCharset_InputCharsetArgumentIsNull_IgnoresCharsetNameCase()
	{
		config::setOutputCharset('windows-1251');
		$spec = new Spec();
		$spec->setInputCharset('WINDOWS-1251');
		$this->assertSame($this->toWindows1251('тестовая строка'), $spec->output->convertToOutputCharset($this->toWindows1251('тестовая строка')));
	}

	public function testConvertToOutputCharset_InputCharsetArgumentIsNull_UsesUtf8AsDefaultInputCharset()
	{
		config::setOutputCharset('utf-8');
		$spec = new Spec();
		$this->assertSame($this->toUtf8('тестовая строка'), $spec->output->convertToOutputCharset($this->toUtf8('тестовая строка')));
	}

	public function testConvertToOutputCharset_InputCharsetArgumentIsNull_GetsInputCharsetFromSelfSpec()
	{
		config::setOutputCharset('utf-8');
		$spec = new Spec();
		
		$spec->setInputCharset('utf-8');
		$this->assertSame($this->toUtf8('тестовая строка'), $spec->output->convertToOutputCharset($this->toUtf8('тестовая строка')));
		
		$spec->setInputCharset('windows-1251');
		$this->assertSame($this->toUtf8('тестовая строка'), $spec->output->convertToOutputCharset($this->toWindows1251('тестовая строка')));
	}
	
	/**
	 * @dataProvider dataProviderConvertToOutputCharset
	 */
	public function testConvertToOutputCharset_InputCharsetArgumentIsSet_ReturnsStringConvertedFromPassedInputCharsetToOutputCharset($inputCharset, $outputCharset, $actualString, $expectedString)
	{
		config::setOutputCharset($outputCharset);
		$spec = new Spec();
		$spec->setInputCharset('koi8-r');
		$this->assertSame($expectedString, $spec->output->convertToOutputCharset($actualString, $inputCharset));
	}
	
	public function testConvertToOutputCharset_InputCharsetArgumentIsSet_IgnoresCharsetNameCase()
	{
		config::setOutputCharset('windows-1251');
		$spec = new Spec();
		$spec->setInputCharset('koi8-r');
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