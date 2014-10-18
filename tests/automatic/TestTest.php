<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic;

require_once __DIR__ . '/../init.php';

class TestTest extends Test {
	public function providerCreateSpecsByVisualPattern() {
		return array(
			// One element only
			
			array('
				0
			', array(
				'0' => array('parents' => array(), 'children' => array()),
			)),
			
			array('
				aaa
			', array(
				'aaa' => array('parents' => array(), 'children' => array()),
			)),
			
			array('
				__aaa__
			', array(
				'aaa' => array('parents' => array(), 'children' => array()),
			)),
			
			// Numbers as name
			
			array('
				  0
				 / \
				1   2
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2')),
				'1' => array('parents' => array('0'), 'children' => array()),
				'2' => array('parents' => array('0'), 'children' => array()),
			)),
			
			array('
				   __0__
				  /     \
				_1_   __2__
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2')),
				'1' => array('parents' => array('0'), 'children' => array()),
				'2' => array('parents' => array('0'), 'children' => array()),
			)),
			
			// Chars as name
			
			array('
				   aaa
				  /   \
				bbb   ccc
			', array(
				'aaa' => array('parents' => array(), 'children' => array('bbb', 'ccc')),
				'bbb' => array('parents' => array('aaa'), 'children' => array()),
				'ccc' => array('parents' => array('aaa'), 'children' => array()),
			)),
			
			array('
				     ____aaa_____
				    /            \
				__bb_bb__    __c_c_c__
			', array(
				'aaa' => array('parents' => array(), 'children' => array('bb_bb', 'c_c_c')),
				'bb_bb' => array('parents' => array('aaa'), 'children' => array()),
				'c_c_c' => array('parents' => array('aaa'), 'children' => array()),
			)),
			
			// Two root elements
			
			array('
				  0   1
				 / \
				2   3
			', array(
				'0' => array('parents' => array(), 'children' => array('2', '3')),
				'1' => array('parents' => array(), 'children' => array()),
				'2' => array('parents' => array('0'), 'children' => array()),
				'3' => array('parents' => array('0'), 'children' => array()),
			)),
			
			// Unnecessary children
			
			array('
				  0
				 / \
				1   2 3 4
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2')),
				'1' => array('parents' => array('0'), 'children' => array()),
				'2' => array('parents' => array('0'), 'children' => array()),
				'3' => array('parents' => array(), 'children' => array()),
				'4' => array('parents' => array(), 'children' => array()),
			)),
			
			// Three levels
			
			array('
					___0___
				   /   |   \
				  1    2    3
				 / \   |
				4   5 aaa
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2', '3')),
				'1' => array('parents' => array('0'), 'children' => array('4', '5')),
				'2' => array('parents' => array('0'), 'children' => array('aaa')),
				'3' => array('parents' => array('0'), 'children' => array()),
				'4' => array('parents' => array('1'), 'children' => array()),
				'5' => array('parents' => array('1'), 'children' => array()),
				'aaa' => array('parents' => array('2'), 'children' => array()),
			)),
			
			// Direct group (lower elements is adding to upper elements)
			
			array('
				    __0__
				   /     \
				  1       2
				 / \     / \
				3   4   5   6
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2')),
				'1' => array('parents' => array('0'), 'children' => array('3', '4')),
				'2' => array('parents' => array('0'), 'children' => array('5', '6')),
				'3' => array('parents' => array('1'), 'children' => array()),
				'4' => array('parents' => array('1'), 'children' => array()),
				'5' => array('parents' => array('2'), 'children' => array()),
				'6' => array('parents' => array('2'), 'children' => array()),
			)),
			
			array('
				     __0__
				    /     \
				   1       2
				 / | \   / | \
				3  4  5 6  7  8
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2')),
				'1' => array('parents' => array('0'), 'children' => array('3', '4', '5')),
				'2' => array('parents' => array('0'), 'children' => array('6', '7', '8')),
				'3' => array('parents' => array('1'), 'children' => array()),
				'4' => array('parents' => array('1'), 'children' => array()),
				'5' => array('parents' => array('1'), 'children' => array()),
				'6' => array('parents' => array('2'), 'children' => array()),
				'7' => array('parents' => array('2'), 'children' => array()),
				'8' => array('parents' => array('2'), 'children' => array()),
			)),
			
			// Reversed group (upper elements is adding to lower elements)
			
			array('
				0   1
				 \ /
				  2
			', array(
				'0' => array('parents' => array(), 'children' => array('2')),
				'1' => array('parents' => array(), 'children' => array('2')),
				'2' => array('parents' => array('0', '1'), 'children' => array()),
			)),
			
			array('
				0  1  2
				 \ | /
				   3
			', array(
				'0' => array('parents' => array(), 'children' => array('3')),
				'1' => array('parents' => array(), 'children' => array('3')),
				'2' => array('parents' => array(), 'children' => array('3')),
				'3' => array('parents' => array('0', '1', '2'), 'children' => array()),
			)),
			
			array('
				0  1 2  3
				 \ | | /
				    4
			', array(
				'0' => array('parents' => array(), 'children' => array('4')),
				'1' => array('parents' => array(), 'children' => array('4')),
				'2' => array('parents' => array(), 'children' => array('4')),
				'3' => array('parents' => array(), 'children' => array('4')),
				'4' => array('parents' => array('0', '1', '2', '3'), 'children' => array()),
			)),
			
			array('
				  0
				 / \
				1   2
				 \ /
				  3
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2')),
				'1' => array('parents' => array('0'), 'children' => array('3')),
				'2' => array('parents' => array('0'), 'children' => array('3')),
				'3' => array('parents' => array('1', '2'), 'children' => array()),
			)),
			
			array('
				   0
				 / | \
				1  2  3
				 \ | /
				   4
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2', '3')),
				'1' => array('parents' => array('0'), 'children' => array('4')),
				'2' => array('parents' => array('0'), 'children' => array('4')),
				'3' => array('parents' => array('0'), 'children' => array('4')),
				'4' => array('parents' => array('1', '2', '3'), 'children' => array()),
			)),
			
			array('
				    0
				 / | | \
				1  2 3  4
				 \ | | /
				    5
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2', '3', '4')),
				'1' => array('parents' => array('0'), 'children' => array('5')),
				'2' => array('parents' => array('0'), 'children' => array('5')),
				'3' => array('parents' => array('0'), 'children' => array('5')),
				'4' => array('parents' => array('0'), 'children' => array('5')),
				'5' => array('parents' => array('1', '2', '3', '4'), 'children' => array()),
			)),
			
			array('
				0
				|
				1   2
				 \ /
				  3
			', array(
				'0' => array('parents' => array(), 'children' => array('1')),
				'1' => array('parents' => array('0'), 'children' => array('3')),
				'2' => array('parents' => array(), 'children' => array('3')),
				'3' => array('parents' => array('1', '2'), 'children' => array()),
			)),
			
			// Direct and reversed groups together
			
			array('
				   0    1   2
				 / | \   \ /
				3  4  5   6
				 \ | /  / | \
				   7   8  9 10
			', array(
				'0' => array('parents' => array(), 'children' => array('3', '4', '5')),
				'1' => array('parents' => array(), 'children' => array('6')),
				'2' => array('parents' => array(), 'children' => array('6')),
				'3' => array('parents' => array('0'), 'children' => array('7')),
				'4' => array('parents' => array('0'), 'children' => array('7')),
				'5' => array('parents' => array('0'), 'children' => array('7')),
				'6' => array('parents' => array('1', '2'), 'children' => array('8', '9', '10')),
				'7' => array('parents' => array('3', '4', '5'), 'children' => array()),
				'8' => array('parents' => array('6'), 'children' => array()),
				'9' => array('parents' => array('6'), 'children' => array()),
				'10' => array('parents' => array('6'), 'children' => array()),
			)),
			
			// "|" relation as single child relation
			
			array('
				   0
				 /   \
				1     2
				|     |
				3     4
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2')),
				'1' => array('parents' => array('0'), 'children' => array('3')),
				'2' => array('parents' => array('0'), 'children' => array('4')),
				'3' => array('parents' => array('1'), 'children' => array()),
				'4' => array('parents' => array('2'), 'children' => array()),
			)),
			
			// "|" relation as group children relation
			
			array('
				     ________0____________
				    /        |            \
				   1       __2__      _____3____
				 / | \    / | | \    /  |  |  | \
				4  5  6  7  8 9 10  11 12 13 14 15
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2', '3')),
				'1' => array('parents' => array('0'), 'children' => array('4', '5', '6')),
				'2' => array('parents' => array('0'), 'children' => array('7', '8', '9', '10')),
				'3' => array('parents' => array('0'), 'children' => array('11', '12', '13', '14', '15')),
				'4' => array('parents' => array('1'), 'children' => array()),
				'5' => array('parents' => array('1'), 'children' => array()),
				'6' => array('parents' => array('1'), 'children' => array()),
				'7' => array('parents' => array('2'), 'children' => array()),
				'8' => array('parents' => array('2'), 'children' => array()),
				'9' => array('parents' => array('2'), 'children' => array()),
				'10' => array('parents' => array('2'), 'children' => array()),
				'11' => array('parents' => array('3'), 'children' => array()),
				'12' => array('parents' => array('3'), 'children' => array()),
				'13' => array('parents' => array('3'), 'children' => array()),
				'14' => array('parents' => array('3'), 'children' => array()),
				'15' => array('parents' => array('3'), 'children' => array()),
			)),
			
			// Mix of "|" relations
			
			array('
				  _______0_______
				 /    |     |    \
				1     2     3     4
				|   / | \   |   / |  \
				5  6  7  8  9  10 11 12
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2', '3', '4')),
				'1' => array('parents' => array('0'), 'children' => array('5')),
				'2' => array('parents' => array('0'), 'children' => array('6', '7', '8')),
				'3' => array('parents' => array('0'), 'children' => array('9')),
				'4' => array('parents' => array('0'), 'children' => array('10', '11', '12')),
				'5' => array('parents' => array('1'), 'children' => array()),
				'6' => array('parents' => array('2'), 'children' => array()),
				'7' => array('parents' => array('2'), 'children' => array()),
				'8' => array('parents' => array('2'), 'children' => array()),
				'9' => array('parents' => array('3'), 'children' => array()),
				'10' => array('parents' => array('4'), 'children' => array()),
				'11' => array('parents' => array('4'), 'children' => array()),
				'12' => array('parents' => array('4'), 'children' => array()),
			)),
			
			// "." relation
			
			array('
					____0___
				   /  |  |  \
				  1   2  3  4
				 / \  .  |
				5   6    7
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2', '3', '4')),
				'1' => array('parents' => array('0'), 'children' => array('5', '6')),
				'2' => array('parents' => array('0'), 'children' => array()),
				'3' => array('parents' => array('0'), 'children' => array('7')),
				'4' => array('parents' => array('0'), 'children' => array()),
				'5' => array('parents' => array('1'), 'children' => array()),
				'6' => array('parents' => array('1'), 'children' => array()),
				'7' => array('parents' => array('3'), 'children' => array()),
			)),
			
			array('
				  0
				 / \
				1   2
				.   |
				    3
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2')),
				'1' => array('parents' => array('0'), 'children' => array()),
				'2' => array('parents' => array('0'), 'children' => array('3')),
				'3' => array('parents' => array('2'), 'children' => array()),
			)),
			
			array('
				  ___0__
				 /  | | \
				1   2 3  4
				 \ /  .  |
				  5     6
				   \   /
				  	 7
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2', '3', '4')),
				'1' => array('parents' => array('0'), 'children' => array('5')),
				'2' => array('parents' => array('0'), 'children' => array('5')),
				'3' => array('parents' => array('0'), 'children' => array()),
				'4' => array('parents' => array('0'), 'children' => array('6')),
				'5' => array('parents' => array('1', '2'), 'children' => array('7')),
				'6' => array('parents' => array('4'), 'children' => array('7')),
				'7' => array('parents' => array('5', '6'), 'children' => array()),
			)),
			
			array('
				  __0__
				 / | | \
				1  2 3  4
				 \ | . /
				   5
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2', '3', '4')),
				'1' => array('parents' => array('0'), 'children' => array('5')),
				'2' => array('parents' => array('0'), 'children' => array('5')),
				'3' => array('parents' => array('0'), 'children' => array()),
				'4' => array('parents' => array('0'), 'children' => array('5')),
				'5' => array('parents' => array('1', '2', '4'), 'children' => array()),
			)),
			
			// "+" relation
			
			array('
				0   1
				| + |
				  2
			', array(
				'0' => array('parents' => array(), 'children' => array('2')),
				'1' => array('parents' => array(), 'children' => array('2')),
				'2' => array('parents' => array('0', '1'), 'children' => array()),
			)),
			
			array('
				    ____0___
				   /  |  |  \
				  1   2  3  4
				 / \+/ \
				5   6   7
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2', '3', '4')),
				'1' => array('parents' => array('0'), 'children' => array('5', '6')),
				'2' => array('parents' => array('0'), 'children' => array('6', '7')),
				'3' => array('parents' => array('0'), 'children' => array()),
				'4' => array('parents' => array('0'), 'children' => array()),
				'5' => array('parents' => array('1'), 'children' => array()),
				'6' => array('parents' => array('1', '2'), 'children' => array()),
				'7' => array('parents' => array('2'), 'children' => array()),
			)),
			
			array('
					___0___
				   / |  |  \
				  1  2  3   4
				 / \+|+/ \
				5    6    7
			', array(
				'0' => array('parents' => array(), 'children' => array('1', '2', '3', '4')),
				'1' => array('parents' => array('0'), 'children' => array('5', '6')),
				'2' => array('parents' => array('0'), 'children' => array('6')),
				'3' => array('parents' => array('0'), 'children' => array('6', '7')),
				'4' => array('parents' => array('0'), 'children' => array()),
				'5' => array('parents' => array('1'), 'children' => array()),
				'6' => array('parents' => array('1', '2', '3'), 'children' => array()),
				'7' => array('parents' => array('3'), 'children' => array()),
			)),
		);
	}

	/**
	 * @dataProvider providerCreateSpecsByVisualPattern
	 */
	public function testCreateSpecsByVisualPattern_ReturnsUniqueSpecsWithProperRelations($pattern, array $expectedResult) {
		$specs = $this->createSpecsByVisualPattern($pattern);
		
		$expectedSpecKeys = array();
		foreach ($expectedResult as $specKey => $relations) {
			$expectedSpecKeys[] = $specKey;
			
			$parentSpecs = array();
			foreach ($relations['parents'] as $relatedSpecKey) {
				$parentSpecs[] = $specs[$relatedSpecKey];
			}
			
			$this->assertSame($parentSpecs, $specs[$specKey]->getParentSpecs());
			
			$childSpecs = array();
			foreach ($relations['children'] as $relatedSpecKey) {
				$childSpecs[] = $specs[$relatedSpecKey];
			}
				
			$this->assertSame($childSpecs, $specs[$specKey]->getChildSpecs());
		}
		
		$this->assertSame($expectedSpecKeys, array_keys($specs));
		$this->assertSame($this->getUniqueArrayElements($specs), $specs);
	}
	
	public function testCreateSpecsByVisualPattern_CreatesAdditionalRelations() {
		$specs = $this->createSpecsByVisualPattern('
			  0
			 / \
			1   2
			|   |
			3   4
		', array(
			'0' => '3',
			'1' => array('2', '4'),
		));
		
		$this->assertSame(array(), $specs['0']->getParentSpecs());
		$this->assertSame(array($specs['1'], $specs['2'], $specs['3']), $specs['0']->getChildSpecs());
		
		$this->assertSame(array($specs['0']), $specs['1']->getParentSpecs());
		$this->assertSame(array($specs['3'], $specs['2'], $specs['4']), $specs['1']->getChildSpecs());
		
		$this->assertSame(array($specs['0'], $specs['1']), $specs['2']->getParentSpecs());
		$this->assertSame(array($specs['4']), $specs['2']->getChildSpecs());
		
		$this->assertSame(array($specs['1'], $specs['0']), $specs['3']->getParentSpecs());
		$this->assertSame(array(), $specs['3']->getChildSpecs());
		
		$this->assertSame(array($specs['2'], $specs['1']), $specs['4']->getParentSpecs());
		$this->assertSame(array(), $specs['4']->getChildSpecs());
	}
	
	public function testCreateSpecsByVisualPattern_DuplicateNamesArePresent_ThrowsException() {
		try {
			$this->createSpecsByVisualPattern('
				   aaa
				  /   \
				aaa   bbb
			');
		} catch (\Exception $e) {
			$this->assertSame('Duplicate name is present on line 3', $e->getMessage());
			return null;
		}

		$this->fail('Should be thrown exception');
	}
	
	public function testCreateSpecsByVisualPattern_UnknownRelationArePresent_ThrowsException() {
		try {
			$this->createSpecsByVisualPattern('
				  0
				 /*\
				1   2
			');
		} catch (\Exception $e) {
			$this->assertSame($e->getMessage(), 'Unknown relation "*" is present on line 2');
			return null;
		}

		$this->fail('Should be thrown exception');
	}

/**/
	
	public function testCreateSpecsByListPattern_ReverseOrder_AddsUpSpecsToBottomSpecsAsParents() {
		$specs = $this->createSpecsByListPattern('
			->->Spec
			->Spec(ccc)
			->->->Spec
			->->Spec
			->Spec(bbb)
			->Spec(aaa)
			Spec
		');

		$this->assertSame(7, count($specs));
		$this->assertSame(array($specs['ccc'], $specs['bbb'], $specs['aaa']), $specs[6]->getParentSpecs());
		$this->assertSame(array(), $specs[6]->getChildSpecs());
		
		$this->assertSame(array(), $specs['aaa']->getParentSpecs());
		$this->assertSame(array($specs[6]), $specs['aaa']->getChildSpecs());
		
		$this->assertSame(array($specs[3]), $specs['bbb']->getParentSpecs());
		$this->assertSame(array($specs[6]), $specs['bbb']->getChildSpecs());
		
		$this->assertSame(array($specs[2]), $specs[3]->getParentSpecs());
		$this->assertSame(array($specs['bbb']), $specs[3]->getChildSpecs());
		
		$this->assertSame(array(), $specs[2]->getParentSpecs());
		$this->assertSame(array($specs[3]), $specs[2]->getChildSpecs());
		
		$this->assertSame(array($specs[0]), $specs['ccc']->getParentSpecs());
		$this->assertSame(array($specs[6]), $specs['ccc']->getChildSpecs());
		
		$this->assertSame(array(), $specs[0]->getParentSpecs());
		$this->assertSame(array($specs['ccc']), $specs[0]->getChildSpecs());
	}
	
	public function testCreateSpecsByListPattern_ReverseOrder_ThrowsExceptionWhenDepthIsBreakMoreThenOne() {
		try {
			$this->createSpecsByListPattern('
				->->Spec
				Spec
			');
		} catch (\Exception $e) {
			return null;
		}

		$this->fail('Should be thrown exception');
	}
	
	public function testCreateSpecsByListPattern_DirectOrder_AddsBottomSpecsToUpSpecsAsChildren() {
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec(aaa)
			->Spec(bbb)
			->->Spec
			->->->Spec
			->Spec(ccc)
			->->Spec
		');

		$this->assertSame(7, count($specs));
		$this->assertSame(array(), $specs[0]->getParentSpecs());
		$this->assertSame(array($specs[0]), $specs['aaa']->getParentSpecs());
		$this->assertSame(array($specs[0]), $specs['bbb']->getParentSpecs());
		$this->assertSame(array($specs['bbb']), $specs[3]->getParentSpecs());
		$this->assertSame(array($specs[3]), $specs[4]->getParentSpecs());
		$this->assertSame(array($specs[0]), $specs['ccc']->getParentSpecs());
		$this->assertSame(array($specs['ccc']), $specs[6]->getParentSpecs());
	}
	
	public function testCreateSpecsByListPattern_DirectOrder_ThrowsExceptionWhenDepthIsBreakMoreThenOne() {
		try {
			$this->createSpecsByListPattern('
				Spec
				->->Spec
			');
		} catch (\Exception $e) {
			return null;
		}

		$this->fail('Should be thrown exception');
	}

	public function testCreateSpecsByListPattern_MixedOrder_AddsUpSpecsToBottomSpecsAsParentsAndAddsBottomSpecsToUpSpecsAsChildren() {
		$specs = $this->createSpecsByListPattern('
			->->Spec
			->Spec
			->->->Spec
			->->Spec
			->Spec
			->Spec
			Spec
			->Spec
			->Spec
			->->Spec
			->->->Spec
			->Spec
			->->Spec
		');
		
		$this->assertSame(13, count($specs));
		
		$this->assertSame(array(), $specs[0]->getParentSpecs());
		$this->assertSame(array($specs[1]), $specs[0]->getChildSpecs());
		
		$this->assertSame(array($specs[0]), $specs[1]->getParentSpecs());
		$this->assertSame(array($specs[6]), $specs[1]->getChildSpecs());
		
		$this->assertSame(array(), $specs[2]->getParentSpecs());
		$this->assertSame(array($specs[3]), $specs[2]->getChildSpecs());
		
		$this->assertSame(array($specs[2]), $specs[3]->getParentSpecs());
		$this->assertSame(array($specs[4]), $specs[3]->getChildSpecs());
		
		$this->assertSame(array($specs[3]), $specs[4]->getParentSpecs());
		$this->assertSame(array($specs[6]), $specs[4]->getChildSpecs());
		
		$this->assertSame(array(), $specs[5]->getParentSpecs());
		$this->assertSame(array($specs[6]), $specs[5]->getChildSpecs());
		
		$this->assertSame(array($specs[1], $specs[4], $specs[5]), $specs[6]->getParentSpecs());
		$this->assertSame(array($specs[7], $specs[8], $specs[11]), $specs[6]->getChildSpecs());
		
		$this->assertSame(array($specs[6]), $specs[7]->getParentSpecs());
		$this->assertSame(array(), $specs[7]->getChildSpecs());
		
		$this->assertSame(array($specs[6]), $specs[8]->getParentSpecs());
		$this->assertSame(array($specs[9]), $specs[8]->getChildSpecs());
		
		$this->assertSame(array($specs[8]), $specs[9]->getParentSpecs());
		$this->assertSame(array($specs[10]), $specs[9]->getChildSpecs());
		
		$this->assertSame(array($specs[9]), $specs[10]->getParentSpecs());
		$this->assertSame(array(), $specs[10]->getChildSpecs());
		
		$this->assertSame(array($specs[6]), $specs[11]->getParentSpecs());
		$this->assertSame(array($specs[12]), $specs[11]->getChildSpecs());
		
		$this->assertSame(array($specs[11]), $specs[12]->getParentSpecs());
		$this->assertSame(array(), $specs[12]->getChildSpecs());
	}

	public function testCreateSpecsByListPattern_ThrowsExceptionWhenNameIsDuplicate() {
		try {
			$this->createSpecsByListPattern('
				Spec(aaa)
				->Spec(aaa)
			');
		} catch (\Exception $e) {
			return null;
		}

		$this->fail('Should be thrown exception');
	}
}