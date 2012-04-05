###Spectrum
Spectrum is a PHP framework for BDD specification test.

**Current version is alpha and not stable.**

Documentation (for a while only on Russian): http://mkharitonov.net/spectrum/

###Examples:
	<?php
	require_once 'spectrum/init.php';

	describe('AddressBook', function(){
		it('Should find person by first name', function(){
			$addressBook = new AddressBook();
			be($addressBook->findPerson('Bob')->firstName)->eq('Bob');
		});
	});

	\spectrum\RootDescribe::run();

Result:

1. AddressBook — success
	1. Should find person by first name — success

**Use [world creators](http://mkharitonov.net/spectrum/#worlds) and [arguments providers](http://mkharitonov.net/spectrum/#arguments-providers) for remove duplications:**

	<?php
	require_once 'spectrum/init.php';

	describe('AddressBook', function(){
		beforeEach(function($w){
			$w->addressBook = new AddressBook();
		});

		it('Should find person by first name', function($w){
			be($w->addressBook->findPerson('Bob')->firstName)->eq('Bob');
		});

		it('Should find person by phone number', array(
			'+7 (495) 123-456-7',
			'(495) 123-456-7',
			'123-456-7',
		), function($w, $phoneNumber){
			be($w->addressBook->findPerson($phoneNumber)->phoneNumber)->eq('+74951234567');
		});
	});

	\spectrum\RootDescribe::run();

Result:

1. AddressBook — success
	1. Should find person by first name — success
	2. Should find person by phone number — success
		1. +7 (495) 123-456-7 — success
		2. (495) 123-456-7 — success
		3. 123-456-7 — success

**Use [contexts](http://mkharitonov.net/spectrum/#contexts) to test existing specs in various configurations:**

	<?php
	require_once 'spectrum/init.php';

	describe('AddressBook', function(){
		beforeEach(function($w){
			$w->addressBook = new AddressBook();
		});

		context('Data storage "MySQL"', function(){
			beforeEach(function($w){ $w->addressBook->setDataStorage('mysql'); });
		});

		context('Data storage "Oracle"', function(){
			beforeEach(function($w){ $w->addressBook->setDataStorage('oracle'); });
		});

		context('Data storage "files"', function(){
			beforeEach(function($w){ $w->addressBook->setDataStorage('files'); });
		});

		it('Should find person by first name', function($w){
			be($w->addressBook->findPerson('Bob')->firstName)->eq('Bob');
		});

		it('Should find person by phone number', array(
			'+7 (495) 123-456-7',
			'(495) 123-456-7',
			'123-456-7',
		), function($w, $phoneNumber){
			be($w->addressBook->findPerson($phoneNumber)->phoneNumber)->eq('+74951234567');
		});
	});

	\spectrum\RootDescribe::run();

Result:

1. AddressBook — success
	1. Data storage "MySQL" — success
		1. Should find person by first name — success
		2. Should find person by phone number — success
			1. +7 (495) 123-456-7 — success
			2. (495) 123-456-7 — success
			3. 123-456-7 — success
	2. Data storage "Oracle" — success
		1. Should find person by first name — success
		2. Should find person by phone number — success
			1. +7 (495) 123-456-7 — success
			2. (495) 123-456-7 — success
			3. 123-456-7 — success
	3. Data storage "files" — success
		1. Should find person by first name — success
		2. Should find person by phone number — success
			1. +7 (495) 123-456-7 — success
			2. (495) 123-456-7 — success
			3. 123-456-7 — success

**Group [contexts](http://mkharitonov.net/spectrum/#contexts) and other specs as you wish:**

	<?php
	require_once 'spectrum/init.php';

	describe('AddressBook', function(){
		beforeEach(function($w){
			$w->addressBook = new AddressBook();
		});

		context('Database storage', function(){
			beforeEach(function($w){ $w->addressBook->setCacheSql(false); });

			context('MySQL', function(){
				beforeEach(function($w){ $w->addressBook->setDataStorage('mysql'); });
			});

			context('Oracle', function(){
				beforeEach(function($w){ $w->addressBook->setDataStorage('oracle'); });
			});
		});

		context('Data storage "files"', function(){
			beforeEach(function($w){ $w->addressBook->setDataStorage('files'); });
		});

		describe('findPerson()', function(){
			it('Should find person by first name', function($w){
				be($w->addressBook->findPerson('Bob')->firstName)->eq('Bob');
			});

			it('Should find person by last name', function($w){
				be($w->addressBook->findPerson('Smith')->lastName)->eq('Smith');
			});
		});
	});

	\spectrum\RootDescribe::run();

Result:

1. AddressBook — success
	1. Database storage — success
		1. MySQL — success
			1. findPerson() — success
				1. Should find person by first name — success
				2. Should find person by last name — success
		2. Oracle — success
			1. findPerson() — success
				1. Should find person by first name — success
				2. Should find person by last name — success
	2. Data storage "files" — success
		1. findPerson() — success
			1. Should find person by first name — success
			2. Should find person by last name — success

**Use "require" statement to place duplicated or big constructions in separate file:**

	<?php
	require_once 'spectrum/init.php';

	describe('AddressBook', function(){
		require(__DIR__ . '/addressBookContexts.php');

		describe('findPerson()', function(){
			it('Should find person by first name', function($w){
				be($w->addressBook->findPerson('Bob')->firstName)->eq('Bob');
			});

			it('Should find person by last name', function($w){
				be($w->addressBook->findPerson('Smith')->lastName)->eq('Smith');
			});
		});
	});

	\spectrum\RootDescribe::run();

Result:

1. AddressBook — success
	1. Database storage — success
		1. MySQL — success
			1. findPerson() — success
				1. Should find person by first name — success
				2. Should find person by last name — success
		2. Oracle — success
			1. findPerson() — success
				1. Should find person by first name — success
				2. Should find person by last name — success
	2. Data storage "files" — success
		1. findPerson() — success
			1. Should find person by first name — success
			2. Should find person by last name — success

###Copyright
(c) Mikhail Kharitonov <mail@mkharitonov.net>

For the full copyright and license information, see the LICENSE.txt file.