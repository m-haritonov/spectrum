<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\types;

interface FunctionTypeInterface {
	public function __construct($function, array $bodyCode);
	
	/**
	 * @param \Closure $function
	 */
	public function setFunction($function);

	/**
	 * @return null|\Closure
	 */
	public function getFunction();

	/**
	 * @param array $code
	 */
	public function setBodyCode(array $bodyCode);
	
	/**
	 * @return array
	 */
	public function getBodyCode();
}