<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

/**
 * @property Assert $not Access to this property to invert result
 * @method Assert eq($expected) Matcher for equal operator
 * @method Assert gt($expected) Matcher for greater than operator
 * @method Assert gte($expected) Matcher for greater than or equal operator
 * @method Assert ident($expected) Matcher for identical operator
 * @method Assert is($expected) Matcher for "is_a" function
 * @method Assert lt($expected) Matcher for less than operator
 * @method Assert lte($expected) Matcher for less than or equal operator
 * @method Assert throwsException($expectedClass = null, $expectedStringInMessage = null, $expectedCode = null) Returns true when code in function with test code throws exception instance of $expectedClass (if not null) with $expectedStringInMessage (if not null) and $expectedCode (if not null)
 */
interface AssertInterface {
	/**
	 * @param mixed $testedValue
	 */
	public function __construct(SpecInterface $ownerSpec, $testedValue);
	
	/**
	 * @param string $matcherName
	 * @return $this
	 */
	public function __call($matcherName, array $matcherArguments = array());
	
	/**
	 * @param string $name
	 */
	public function __get($name);
}