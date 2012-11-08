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
			world()->addressBook = new AddressBook();
		});

		context('"MySql" driver', function(){
			beforeEach(function(){
				world()->addressBook->setDriver(new drivers\MySql());
			});
		});

		context('"Files" driver', function(){
			beforeEach(function(){
				world()->addressBook->setDriver(new drivers\Files());
			});
		});

		it('Should find person by first name', function(){
			$person = world()->addressBook->findPerson('Bob');
			verify($person->firstName, '==', 'Bob');
		});

		it('Should find person by phone number in any format', array(
			'+7 (495) 123-456-7',
			'(495) 123-456-7',
			'123-456-7',
		), function($phoneNumber){
			$person = world()->addressBook->findPerson($phoneNumber);
			verify($person->phoneNumber, '==', '+74951234567');
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