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
		public $firstName = 'Bob';
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
		beforeEach(function(){
			// Use "world()" instead of "$this" in php 5.3, "$this" available only in php >= 5.4
			$this->addressBook = new AddressBook();
		});

		context('"MySql" driver', function(){
			beforeEach(function(){
				$this->addressBook->setDriver(new drivers\MySql());
			});
		});

		context('"Files" driver', function(){
			beforeEach(function(){
				$this->addressBook->setDriver(new drivers\Files());
			});
		});

		it('Should find person by first name', function(){
			the($this->addressBook->findPerson('Bob')->firstName)->eq('Bob');
		});

		it('Should find person by phone number', array(
			'+7 (495) 123-456-7',
			'(495) 123-456-7',
			'123-456-7',
		), function($phoneNumber){
			the($this->addressBook->findPerson($phoneNumber)->phoneNumber)->eq('+74951234567');
		});
	});

	\spectrum\RootDescribe::run();
}