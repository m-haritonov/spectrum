#Spectrum
Spectrum is a PHP framework for test automation.

Framework files are in the "US-ASCII" charset (file "notes.txt" is in the "UTF-8" charset).

**Current version is alpha and not stable.**

##System requirements
The framework requires PHP 5.3 or later.

##Example
	<?php
	require_once __DIR__ . '/spectrum/spectrum/init.php';

	group('"AddressBook" class', function(){
		before(function(){
			data()->addressBook = new AddressBook();
		});
		
		group('"MySql" driver', function(){
			before(function(){
				data()->addressBook->setDriver(new drivers\MySql());
			});
		});
	
		group('"FileSystem" driver', function(){
			before(function(){
				data()->addressBook->setDriver(new drivers\FileSystem());
			});
		});
	}, function(){
		group('"findPerson" method', function(){
			test('Returns person by first name', function(){
				$person = data()->addressBook->findPerson('Bob');
				be($person->firstName)->eq('Bob');
			});
		
			test('Returns person by phone number', array(
				array('phoneNumber' => '+7 (495) 123-456-7'),
				array('phoneNumber' => '(495) 123-456-7'),
				array('phoneNumber' => '123-456-7'),
			), function(){
				$person = data()->addressBook->findPerson(data()->phoneNumber);
				be($person->phoneNumber)->eq('+74951234567');
			});
		});
	});
	
	\spectrum\run();

Result:

1. "AddressBook" class - success
	1. "MySql" driver - success
		1. "findPerson" method - success
			1.  Returns person by first name - success
			2. Returns person by phone number - success
				1.  +7 (495) 123-456-7 - success
				2.  (495) 123-456-7 - success
				3.  123-456-7 - success
	2. "FileSystem" driver - success
		1. "findPerson" method - success
			1.  Returns person by first name - success
			2. Returns person by phone number - success
				1.  +7 (495) 123-456-7 - success
				2.  (495) 123-456-7 - success
				3.  123-456-7 - success


##Copyright, contacts and license
Copyright (c) 2011-2014 Mikhail Kharitonov (<mail@mkharitonov.net>). All rights reserved.

The project is licensed under the "New BSD License" (see text below).

Redistribution and use in source and binary forms, with or without modification,
are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this
   list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright notice,
   this list of conditions and the following disclaimer in the documentation
   and/or other materials provided with the distribution.

3. Neither the name of the copyright holder nor the names of its contributors
   may be used to endorse or promote products derived from this software without
   specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.