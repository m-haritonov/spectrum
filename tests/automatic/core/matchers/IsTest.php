<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\matchers;

require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../../spectrum/core/matchers/is.php';

class IsTest extends \spectrum\tests\automatic\Test {
	public function provider() {
		$ancestor['interface'] = \spectrum\tests\_testware\tools::createInterface('interface ... {}');
		$ancestor['class'] = \spectrum\tests\_testware\tools::createClass('class ... implements ' . $ancestor['interface'] . ' {}');
		$ancestor['instance'] = new $ancestor['class']();
		
		$parent['interface'] = \spectrum\tests\_testware\tools::createInterface('interface ... extends ' . $ancestor['interface'] . ' {}');
		$parent['class'] = \spectrum\tests\_testware\tools::createClass('class ... extends ' . $ancestor['class'] . ' implements ' . $parent['interface'] . ' {}');
		$parent['instance'] = new $parent['class']();
		
		$heir['interface'] = \spectrum\tests\_testware\tools::createInterface('interface ... extends ' . $parent['interface'] . ' {}');
		$heir['class'] = \spectrum\tests\_testware\tools::createClass('class ... extends ' . $parent['class'] . ' implements ' . $heir['interface'] . ' {}');
		$heir['instance'] = new $heir['class']();
		
		$other['interface'] = \spectrum\tests\_testware\tools::createInterface('interface ... {}');
		$other['class'] = \spectrum\tests\_testware\tools::createClass('class ... implements ' . $other['interface'] . ' {}');
		$other['instance'] = new $other['class']();

		return array(
			array($heir['interface'], $heir['interface'], true),
			array($heir['interface'], $parent['interface'], true),
			array($heir['interface'], $ancestor['interface'], true),
			
			array($heir['class'], $heir['interface'], true),
			array($heir['class'], $heir['class'], true),
			array($heir['class'], $heir['instance'], true),
			array($heir['class'], $parent['interface'], true),
			array($heir['class'], $parent['class'], true),
			array($heir['class'], $parent['instance'], true),
			array($heir['class'], $ancestor['interface'], true),
			array($heir['class'], $ancestor['class'], true),
			array($heir['class'], $ancestor['instance'], true),

			array($heir['instance'], $heir['interface'], true),
			array($heir['instance'], $heir['class'], true),
			array($heir['instance'], $heir['instance'], true),
			array($heir['instance'], $parent['interface'], true),
			array($heir['instance'], $parent['class'], true),
			array($heir['instance'], $parent['instance'], true),
			array($heir['instance'], $ancestor['interface'], true),
			array($heir['instance'], $ancestor['class'], true),
			array($heir['instance'], $ancestor['instance'], true),
			
			//
			
			array($heir['interface'], $other['interface'], false),
			array($heir['interface'], $other['class'], false),
			array($heir['interface'], $other['instance'], false),
			array($heir['interface'], $parent['class'], false),
			array($heir['interface'], $parent['instance'], false),
			array($heir['interface'], $ancestor['class'], false),
			array($heir['interface'], $ancestor['instance'], false),
			
			array($heir['class'], $other['interface'], false),
			array($heir['class'], $other['class'], false),
			array($heir['class'], $other['instance'], false),
			
			array($heir['instance'], $other['interface'], false),
			array($heir['instance'], $other['class'], false),
			array($heir['instance'], $other['instance'], false),
			
			array($heir['instance'], '', false),
			array($heir['instance'], 0, false),
			array($heir['instance'], true, false),
			array($heir['instance'], false, false),
			array($heir['instance'], null, false),
			
			array('', $heir['instance'], false),
			array(0, $heir['instance'], false),
			array(true, $heir['instance'], false),
			array(false, $heir['instance'], false),
			array(null, $heir['instance'], false),
		);
	}
	
	/**
	 * @dataProvider provider
	 */
	public function test($heir, $parent, $result) {
		$this->assertSame($result, \spectrum\core\matchers\is(new \spectrum\core\details\MatcherCall(), $heir, $parent));
	}
}