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

class CharsetTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::setAllowInputCharsetModify(true);
	}
	
	public function testSetInputCharset_SetsNewCharset()
	{
		$spec = new Spec();
		$spec->charset->setInputCharset('windows-1251');
		$this->assertSame('windows-1251', $spec->charset->getInputCharset());
		
		$spec->charset->setInputCharset('koi8-r');
		$this->assertSame('koi8-r', $spec->charset->getInputCharset());
	}
	
	public function testSetInputCharset_CallOnRun_ThrowsExceptionAndDoesNotChangeCharset()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->charset->setInputCharset("koi8-r");
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		

		$spec = new Spec();
		$spec->charset->setInputCharset('windows-1251');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\basePlugins\Charset::setInputCharset" method is forbidden on run', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame('windows-1251', $spec->charset->getInputCharset());
	}
	
	public function testSetInputCharset_InputCharsetModifyDenyInConfig_ThrowsExceptionAndDoesNotChangeCharset()
	{
		$spec = new Spec();
		$spec->charset->setInputCharset('windows-1251');
		
		config::setAllowInputCharsetModify(false);
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Input charset modify deny in config', function() use($spec){
			$spec->charset->setInputCharset('koi8-r');
		});
		
		$this->assertSame('windows-1251', $spec->charset->getInputCharset());
	}
	
/**/
	
	public function testGetInputCharset_ReturnsSetCharset()
	{
		$spec = new Spec();
		$spec->charset->setInputCharset('windows-1251');
		$this->assertSame('windows-1251', $spec->charset->getInputCharset());
		
		$spec->charset->setInputCharset('koi8-r');
		$this->assertSame('koi8-r', $spec->charset->getInputCharset());
	}
	
	public function testGetInputCharset_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->charset->getInputCharset());
	}

/**/
	
	public function testGetInputCharsetThroughRunningAncestors_ReturnsCharsetFromRunningAncestorOrFromSelf()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->charset->getInputCharsetThroughRunningAncestors();
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->Spec(parent3)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2', 'parent2' => 'endingSpec2'));
		
		$specs[0]->charset->setInputCharset('windows-1251');
		$specs['endingSpec1']->charset->setInputCharset('windows-1252');
		$specs['parent1']->charset->setInputCharset('windows-1253');
		$specs['parent2']->charset->setInputCharset('windows-1254');
		
		$specs[0]->run();
		
		$this->assertSame(array('windows-1252', 'windows-1253', 'windows-1254', 'windows-1251'), \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testGetInputCharsetThroughRunningAncestors_ReturnsUtf8ByDefault()
	{
		$spec = new Spec();
		$this->assertSame('utf-8', $spec->charset->getInputCharsetThroughRunningAncestors());
	}
}