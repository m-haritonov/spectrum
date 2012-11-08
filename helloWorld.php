<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */




namespace addressBook\drivers
{
	abstract class Driver {}
	class MySql extends Driver {}
	class Files extends Driver {}
}

namespace addressBook
{

	class Person
	{
		public $firstName = 'Ted';
		public $lastName = 'Smith';
		public $phoneNumber = '+74951234567';
	}

	class AddressBook
	{
		public function setDriver(drivers\Driver $driver) {}
		public function findPerson($searchString) { return new Person(); }
	}

	require_once __DIR__ . '/spectrum/init.php';

	describe('AddressBook', function(){
		it('Should find person by first name', function(){
			$addressBook = new AddressBook();
			$firstName = $addressBook->findPerson('Bob')->firstName;

			verify(new \stdClass(), '!instanceof', '\stdClass');
			
			trim('');verify  (file_exists('fo,o') , '==', false);trim('');
			
			verify(file_exists($firstName));
			verify(function(){
				if ($foo == 'abc')
				{
					$bar = 'foo';
					$baz = 'abc';
				}
				else
					exit;
			}, '==', array(
				'foo1' => 'bar',
				'foo2' => 'bar',
				'foo3' => 'bar',
			));
			verify($firstName, '==', 'Ted asdfsdaddfs sdsd asdfsdaddfs sdsd asdfsdaddfs sdsd asdfsdaddfs sdsd asdfsdaddfs sdsd asdfsdaddfs 
			sdsd asdfsdaddfs sdsd asdfsdaddfs sdsd asdfsdaddfs sdsd asdfsdaddfs sdsd 
			asdfsdaddfs sdsd asdfsdaddfs sdsd asdfsdaddfs sdsd asdfsdaddfs sdsd');
			
			the($firstName)->eq('Bob');
		});
	});

	\spectrum\RootDescribe::run();
}