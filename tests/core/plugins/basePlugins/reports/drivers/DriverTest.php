<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins\basePlugins\reports\drivers;
use spectrum\config;
use spectrum\core\plugins\basePlugins\reports\drivers\Driver;
use spectrum\core\plugins\basePlugins\reports\Reports;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../../../init.php';

class DriverTest extends \spectrum\tests\Test
{
	public function testGetIndention_ReturnsIndentionStringFromConfigWithSpecifiedRepeats()
	{
		$driver = $this->createDriver();
		
		config::setOutputIndention('a');
		$this->assertSame('a', $driver->getIndention());
		$this->assertSame('aa', $driver->getIndention(2));
		$this->assertSame('aaa', $driver->getIndention(3));
		
		config::setOutputIndention('bb');
		$this->assertSame('bb', $driver->getIndention());
		$this->assertSame('bbbb', $driver->getIndention(2));
		$this->assertSame('bbbbbb', $driver->getIndention(3));
	}
	
/**/
	
	public function testPrependIndentionToEachLine_AddsIndentionToEachLineWithSpecifiedRepeats()
	{
		config::setOutputIndention('--');
		config::setOutputNewline("\n");
		
		$driver = $this->createDriver();
		
		$this->assertSame("--aaa", $driver->prependIndentionToEachLine("aaa"));
		$this->assertSame("--aaa\n--bbb\n--ccc", $driver->prependIndentionToEachLine("aaa\nbbb\nccc"));
		
		$this->assertSame("----aaa", $driver->prependIndentionToEachLine("aaa", 2));
		$this->assertSame("----aaa\n----bbb\n----ccc", $driver->prependIndentionToEachLine("aaa\nbbb\nccc", 2));
		
		$this->assertSame("------aaa", $driver->prependIndentionToEachLine("aaa", 3));
		$this->assertSame("------aaa\n------bbb\n------ccc", $driver->prependIndentionToEachLine("aaa\nbbb\nccc", 3));
	}
	
	public function testPrependIndentionToEachLine_TrimNewlineIsTrue_TrimNewline()
	{
		config::setOutputIndention('--');
		config::setOutputNewline("\n");
		
		$driver = $this->createDriver();
		$this->assertSame("--aaa\n--bbb", $driver->prependIndentionToEachLine("\naaa\nbbb\n", 1, true));
	}
	
	public function testPrependIndentionToEachLine_TrimNewlineIsFalse_DoesNotTrimNewline()
	{
		config::setOutputIndention('--');
		config::setOutputNewline("\n");
		
		$driver = $this->createDriver();
		
		$this->assertSame("--\n--aaa\n--bbb\n--", $driver->prependIndentionToEachLine("\naaa\nbbb\n", 1, false));
	}
	
/**/
	
	public function testGetNewline_ReturnsNewlineStringFromConfigWithSpecifiedRepeats()
	{
		$driver = $this->createDriver();
		
		config::setOutputNewline('a');
		$this->assertSame('a', $driver->getNewline());
		$this->assertSame('aa', $driver->getNewline(2));
		$this->assertSame('aaa', $driver->getNewline(3));
		
		config::setOutputNewline('bb');
		$this->assertSame('bb', $driver->getNewline());
		$this->assertSame('bbbb', $driver->getNewline(2));
		$this->assertSame('bbbbbb', $driver->getNewline(3));
	}
	
/**/
	
	public function testTrimNewline_RemovesNewlineFromBeginAndEndOfText()
	{
		$driver = $this->createDriver();
		config::setOutputNewline("\n");
		$this->assertSame("bb\nbb", $driver->trimNewline("bb\nbb"));
		$this->assertSame("bb\nbb", $driver->trimNewline("\nbb\nbb\n"));
		$this->assertSame("bb\nbb", $driver->trimNewline("\n\n\nbb\nbb\n\n\n"));
		$this->assertSame(" \nbb\nbb\n ", $driver->trimNewline(" \nbb\nbb\n "));
	}
	
	public function testTrimNewline_DoesNotRemoveSpaces()
	{
		$driver = $this->createDriver();
		config::setOutputNewline("\n");
		$this->assertSame(" \nbb\nbb\n ", $driver->trimNewline(" \nbb\nbb\n "));
	}
	
/**/
	
	public function testTranslate_ReturnsStringWithReplacements()
	{
		$driver = $this->createDriver();
		$this->assertSame('text "some text 1" is "some text 2"', $driver->translate('text "%aaa%" is "%bbb%"', array('%aaa%' => 'some text 1', '%bbb%' => 'some text 2')));
	}
	
/**/

	/**
	 * @return Driver
	 */
	private function createDriver()
	{
		$driverClass = $this->createClass('
			class ... extends \spectrum\core\plugins\basePlugins\reports\drivers\Driver
			{
				public function getContentBeforeSpec(){}
				public function getContentAfterSpec(){}
				public function createComponent($name){}
			}
		');
		return new $driverClass(new Reports(new Spec()));
	}
}