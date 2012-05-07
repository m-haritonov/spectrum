###Spectrum
Spectrum is a PHP framework for BDD specification test.

**Current version is alpha and not stable.**

Documentation (for a while only on Russian):
http://mkharitonov.net/spectrum/
https://bitbucket.org/mkharitonov/spectrum-framework.org/src

###Examples:
	<?php
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

Result:

1. AddressBook — success
	1. "MySql" driver — success
		1. Should find person by first name — success
		2. Should find person by phone number — success
			1. +7 (495) 123-456-7 — success
			2. (495) 123-456-7 — success
			3. 123-456-7 — success
	2. "Files" driver — success
		1. Should find person by first name — success
		2. Should find person by phone number — success
			1. +7 (495) 123-456-7 — success
			2. (495) 123-456-7 — success
			3. 123-456-7 — success

###Copyright
(c) Mikhail Kharitonov <mail@mkharitonov.net>

For the full copyright and license information, see the LICENSE.txt file.