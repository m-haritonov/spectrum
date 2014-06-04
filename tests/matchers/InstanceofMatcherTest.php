<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\matchers;

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../../spectrum/matchers/instanceofMatcher.php';

class InstanceofMatcherTest extends \spectrum\tests\Test
{
	public function providerReturnsTrue()
	{
		$ancestor['interface'] = $this->createInterface('interface ... {}');
		$ancestor['class'] = $this->createClass('class ... implements ' . $ancestor['interface'] . ' {}');
		$ancestor['instance'] = new $ancestor['class']();
		
		$parent['interface'] = $this->createInterface('interface ... extends ' . $ancestor['interface'] . ' {}');
		$parent['class'] = $this->createClass('class ... extends ' . $ancestor['class'] . ' implements ' . $parent['interface'] . ' {}');
		$parent['instance'] = new $parent['class']();
		
		$heir['interface'] = $this->createInterface('interface ... extends ' . $parent['interface'] . ' {}');
		$heir['class'] = $this->createClass('class ... extends ' . $parent['class'] . ' implements ' . $heir['interface'] . ' {}');
		$heir['instance'] = new $heir['class']();
		
		return array(
			array($heir['interface'], $parent['interface']),
			array($heir['interface'], $ancestor['interface']),
	
			array($heir['class'], $parent['interface']),
			array($heir['class'], $parent['class']),
			array($heir['class'], $parent['instance']),
			array($heir['class'], $ancestor['interface']),
			array($heir['class'], $ancestor['class']),
			array($heir['class'], $ancestor['instance']),
		
			array($heir['instance'], $parent['interface']),
			array($heir['instance'], $parent['class']),
			array($heir['instance'], $parent['instance']),
			array($heir['instance'], $ancestor['interface']),
			array($heir['instance'], $ancestor['class']),
			array($heir['instance'], $ancestor['instance']),
		);
	}
	
	/**
	 * @dataProvider providerReturnsTrue
	 */
	public function testFirstArgumentIsInstanceOfSecondArgument_ReturnsTrue($heir, $parent)
	{
		$this->assertTrue(\spectrum\matchers\instanceofMatcher($heir, $heir));
		$this->assertTrue(\spectrum\matchers\instanceofMatcher($heir, $parent));
	}
	
/**/
	
	public function providerReturnsFalse1()
	{
		$heir['interface'] = $this->createInterface('interface ... {}');
		$heir['class'] = $this->createClass('class ... implements ' . $heir['interface'] . ' {}');
		$heir['instance'] = new $heir['class']();
		
		$other['interface'] = $this->createInterface('interface ... {}');
		$other['class'] = $this->createClass('class ... implements ' . $other['interface'] . ' {}');
		$other['instance'] = new $other['class']();

		return array(
			array($heir['interface'], $other['interface']),
			array($heir['interface'], $other['class']),
			array($heir['interface'], $other['instance']),

			array($heir['class'], $other['interface']),
			array($heir['class'], $other['class']),
			array($heir['class'], $other['instance']),

			array($heir['instance'],  $other['interface']),
			array($heir['instance'], $other['class']),
			array($heir['instance'], $other['instance']),
		);
	}
	
	/**
	 * @dataProvider providerReturnsFalse1
	 */
	public function testFirstArgumentIsNotInstanceOfSecondArgument_ReturnsFalse($heir, $other)
	{
		$this->assertFalse(\spectrum\matchers\instanceofMatcher($heir, $other));
	}
	
/**/
	
	public function providerReturnsFalse2()
	{
		$ancestor['interface'] = $this->createInterface('interface ... {}');
		$ancestor['class'] = $this->createClass('class ... implements ' . $ancestor['interface'] . ' {}');
		$ancestor['instance'] = new $ancestor['class']();
		
		$parent['interface'] = $this->createInterface('interface ... extends ' . $ancestor['interface'] . ' {}');
		$parent['class'] = $this->createClass('class ... extends ' . $ancestor['class'] . ' implements ' . $parent['interface'] . ' {}');
		$parent['instance'] = new $parent['class']();
		
		$heir['interface'] = $this->createInterface('interface ... extends ' . $parent['interface'] . ' {}');
		$heir['class'] = $this->createClass('class ... extends ' . $parent['class'] . ' implements ' . $heir['interface'] . ' {}');
		$heir['instance'] = new $heir['class']();
		
		return array(
			array($heir['interface'], $parent['class']),
			array($heir['interface'], $parent['instance']),
			array($heir['interface'], $ancestor['class']),
			array($heir['interface'], $ancestor['instance']),
		);
	}
	
	/**
	 * @dataProvider providerReturnsFalse2
	 */
	public function testFirstArgumentIsInterfaceAndSecondArgumentIsNotInterfaceAncestor_ReturnsFalse($heir, $parent)
	{
		$this->assertFalse(\spectrum\matchers\instanceofMatcher($heir, $parent));
	}
}