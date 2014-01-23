###Spectrum
Spectrum is a PHP framework for BDD specification test. Works in php 5.3 and newer.

**Current version is alpha and not stable.**

Web site: [http://spectrum-framework.org/][1]

###Example:
	<?php
	require_once __DIR__ . '/spectrum/init.php';

	group('"AddressBook" class', function(){
		before(function(){ this()->addressBook = new AddressBook(); });
		
		group('"MySql" driver', function(){
			before(function(){ this()->addressBook->setDriver(new drivers\MySql()); });
		});
	
		group('"FileSystem" driver', function(){
			before(function(){ this()->addressBook->setDriver(new drivers\FileSystem()); });
		});
	}, function(){
		group('"findPerson" method', function(){
			test('Returns person by first name', function(){
				$person = this()->addressBook->findPerson('Bob');
				be($person->firstName)->eq('Bob');
			});
		
			test('Returns person by phone number', array(
				array('phoneNumber' => '+7 (495) 123-456-7'),
				array('phoneNumber' => '(495) 123-456-7'),
				array('phoneNumber' => '123-456-7'),
			), function(){
				$person = this()->addressBook->findPerson(this()->phoneNumber);
				be($person->phoneNumber)->eq('+74951234567');
			});
		});
	});
	
	\spectrum\root()->run();

Result:

1. "AddressBook" class — success
	1. "MySql" driver — success
		1. "findPerson" method — success
			1.  Returns person by first name — success
			2. Returns person by phone number — success
				1.  +7 (495) 123-456-7 — success
				2.  (495) 123-456-7 — success
				3.  123-456-7 — success
	2. "FileSystem" driver — success
		1. "findPerson" method — success
			1.  Returns person by first name — success
			2. Returns person by phone number — success
				1.  +7 (495) 123-456-7 — success
				2.  (495) 123-456-7 — success
				3.  123-456-7 — success

###Copyright and license
Project is licensed under the "New BSD License". For the copyright and license information, see the LICENSE.txt file 
that was distributed with this source code.

###Contacts
Mikhail Kharitonov <mail@mkharitonov.net>

[1]: http://spectrum-framework.org/