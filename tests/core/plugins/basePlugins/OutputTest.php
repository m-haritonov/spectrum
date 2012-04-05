<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;
require_once dirname(__FILE__) . '/../../../init.php';
use spectrum\core\Config;

class OutputTest extends Test
{
	public function testSetInputEncoding_ShouldBeThrowExceptionIfNotAllowInputEncodingModify()
	{
		Config::setAllowInputEncodingModify(false);
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Input encoding modify deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->output->setInputEncoding('koi-8');
		});
	}

/**/

	public function testSetOutputEncoding_ShouldBeThrowExceptionIfNotAllowOutputEncodingModify()
	{
		Config::setAllowOutputEncodingModify(false);
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Output encoding modify deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->output->setOutputEncoding('koi-8');
		});
	}

/**/

	public function dataProviderConvertToOutputEncoding()
	{
		return array(
			array('utf-8',        'utf-8',        $this->toUtf8('привет мир'),        $this->toUtf8('привет мир')),
			array('utf-8',        'windows-1251', $this->toUtf8('привет мир'),        $this->toWindows1251('привет мир')),
			array('windows-1251', 'utf-8',        $this->toWindows1251('привет мир'), $this->toUtf8('привет мир')),
			array('windows-1251', 'windows-1251', $this->toWindows1251('привет мир'), $this->toWindows1251('привет мир')),
		);
	}

	/**
	 * @dataProvider dataProviderConvertToOutputEncoding
	 */
	public function testConvertToOutputEncoding_ShouldBeConvertStringFromInputToOutputEncoding($inputEncoding, $outputEncoding, $actualString, $expectedString)
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->output->setInputEncoding($inputEncoding);
		$it->output->setOutputEncoding($outputEncoding);
		$it->setName($actualString);
		$this->assertEquals($expectedString, $it->output->convertToOutputEncoding($it->getName()));
	}

	public function testConvertToOutputEncoding_ShouldBeUseUtf8AsInputAndOutputEncodingByDefault()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->setName($this->toUtf8('привет мир'));
		$this->assertEquals($this->toUtf8('привет мир'), $it->output->convertToOutputEncoding($it->getName()));
	}

	public function testConvertToOutputEncoding_ShouldBeGetInputEncodingFromAncestorsIfNotSet()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It
		');

		$specs[0]->output->setInputEncoding('windows-1251');
		$specs[0]->output->setOutputEncoding('utf-8');

		$specs[1]->setName($this->toWindows1251('привет мир'));
		$this->assertEquals($this->toUtf8('привет мир'), $specs[1]->output->convertToOutputEncoding($specs[1]->getName()));
	}

	public function testConvertToOutputEncoding_ShouldBeGetOutputEncodingFromAncestorsIfNotSet()
	{
		$specs = $this->createSpecsTree('
			Describe
			->It
		');

		$specs[0]->output->setInputEncoding('utf-8');
		$specs[0]->output->setOutputEncoding('windows-1251');

		$specs[1]->setName($this->toUtf8('привет мир'));
		$this->assertEquals($this->toWindows1251('привет мир'), $specs[1]->output->convertToOutputEncoding($specs[1]->getName()));
	}

/**/

	public function testPut_ShouldBePrintStringInCorrectEncoding()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->output->setInputEncoding('utf-8');
		$it->output->setOutputEncoding('utf-8');
		$it->setName($this->toUtf8('привет мир'));

		ob_start();
		$it->output->put($it->getName());
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertEquals($this->toUtf8('привет мир'), $output);
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