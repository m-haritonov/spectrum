<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

require_once dirname(__FILE__) . '/../spectrum/init.php';

class Person
{
	public $firstName = 'Bob';
	public $lastName = 'Smith';
	public $phoneNumber = '+74951234567';
}

class AddressBook
{
	public function setDataStorage($dataStorage) {}
	public function setCacheSql($enable) {}

	public function findPerson($searchString) { return new Person(); }
}

class Foo_ofdsjifdfjisfjsfdsfdidfsafdsafisafdasfasfdasfsafdsfaf
{
	public $publ = 111;
	protected $protect = 222;
	private $priv = 333;
	static private $statpriv = 444;
}

describe('Doors', function(){
	describe('Doors', function(){
		it('Кол-во дверей должно быть', function(){
			the("\r\n\r\naaa\r\nbb\r\n\r\n")->eq("\ta\taa\r\nbb   b\r\n");
			the("\r\n\r\naaa\r\nbb\r\n\r\n")->not->eq("\ta\taa\r\nbb   b\r\n");
		});
		it('Кол-во дверей должно быть', function(){});
	});

	it('Кол-во дверей должно быть', function(){});

	describe('Doors', function(){
		context('На земле', function(){
			beforeEach(function(){
				$w->doors = 3;
			});
		});
		context('На земле', function(){
			beforeEach(function(){
				$w->doors = 4;
			});
		});

		it('Кол-во дверей должно быть 3', function($w){

		});

		it('Кол-во окон должно быть ', function($w){
			the($w->doors)->eq(3);
			the($w->doors)->eq(4);
			the($w->doors)->eq(3);
		});

		it('Кол-во колес должно быть 4', function($w){
			the($w->doors)->eq(3);
			the($w->doors)->eq(4);
			the($w->doors)->eq(3);
		});


	});
});

describe('AddressBook', function(){

	context('На земле', function(){});

	addPattern('Автомобить', function($doorsCount){
		describe('sadf', function() use($doorsCount){
			it('Кол-во дверей должно быть ' . $doorsCount, function($w) use($doorsCount){
				the(4)->eq($doorsCount);
				the(4)->eq($doorsCount);
				the(3)->eq($doorsCount);
				the(2)->eq($doorsCount);
				the(4)->eq($doorsCount);
				the(true)->eq(false);
				the(3.688)->eq(4.55);
				the(function($a, $b){ return $a == $b; })->eq(function(){});
				the(456)->eq(64564456445464545454564684884);
				the(null)->eq(null);
				the('sdfsdfsdfsdfsdfsdfsdflsijsfdj')->eq('45fdsdsdf');
				the("\r\n\r\naaa\r\nbb\r\n\r\n")->eq("\ta\taa\r\nbb   b\r\n");
				the(fopen(__FILE__, 'r'))->eq(fopen(__FILE__, 'r'));
				the(array('foo' => "\r\nasfdsfd\r\nsarsare\r", 'bar' => 123, 'baz' => array(1, 2, 3)))->eq(array('foo' => 'jcv', 'bar' => 123, 'baz' => array(1, 2, 5)));
				the(array())->eq(array());

				$obj = new Foo_ofdsjifdfjisfjsfdsfdidfsafdsafisafdasfasfdasfsafdsfaf();
				$obj->foo = 'qqq';
				$obj->bar = 12345;
				$obj->baz = new Foo_ofdsjifdfjisfjsfdsfdidfsafdsafisafdasfasfdasfsafdsfaf;

				the($obj)->eq($obj);
				message('safdsfsafd');
				message('fffsdfsdf sdfdsfdfsdfsdfsfd');

				throw new \spectrum\core\asserts\Exception('Foo bar baz abc');
			});

			it('Кол-во дверей должно быть ' . $doorsCount, function($w) use($doorsCount){});
			it('Кол-во дверей должно быть ' . $doorsCount, function($w) use($doorsCount){});
			it('Кол-во дверей должно быть ' . $doorsCount, function($w) use($doorsCount){ message('safdsfsafd'); the(true)->true(); });
		});
	});

	itLikePattern('Автомобить', 3);
	itLikePattern('Автомобить', 4);
});

describe('ActionList', function(){
	describe('Doors', function(){
		it('Кол-во дверей должно быть', function(){});
		it('Кол-во дверей должно быть', function(){});
	});

	it('Кол-во дверей должно быть', function(){});

	describe('Doors', function(){
		it('Кол-во дверей должно быть', function(){});
		it('Кол-во дверей должно быть', function(){});
	});
});

describe('FlyCar', function(){
	describe('Doors', function(){
		it('Кол-во дверей должно быть', function(){});
		it('Кол-во дверей должно быть', function(){});
	});

	it('Кол-во дверей должно быть', function(){});

	describe('Doors', function(){
		it('Кол-во дверей должно быть', function(){});
		it('Кол-во дверей должно быть', function(){});
	});
});

describe('FooBar', function(){
	describe('Doors', function(){
		it('Кол-во дверей должно быть', function(){});
		it('Кол-во дверей должно быть', function(){});
	});

	it('Кол-во дверей должно быть', function(){});

	describe('Doors', function(){
		it('Кол-во дверей должно быть', function(){});
		it('Кол-во дверей должно быть', function(){});
	});
});

\spectrum\RootDescribe::run();