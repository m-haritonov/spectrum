<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\models;

/**
 * @property Assertion $not Access to this property to invert result
 * @method Assertion eq($expected) Matcher for equal operator
 * @method Assertion gt($expected) Matcher for greater than operator
 * @method Assertion gte($expected) Matcher for greater than or equal operator
 * @method Assertion ident($expected) Matcher for identical operator
 * @method Assertion is($expected) Matcher for "is_a" function
 * @method Assertion lt($expected) Matcher for less than operator
 * @method Assertion lte($expected) Matcher for less than or equal operator
 * @method Assertion throwsException($expectedClass = null, $expectedStringInMessage = null, $expectedCode = null) Returns true when code in function with test code throws exception instance of $expectedClass (if not null) with $expectedStringInMessage (if not null) and $expectedCode (if not null)
 */
interface AssertionInterface {
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