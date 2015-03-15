<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\models\types;

class FunctionType implements FunctionTypeInterface {
	protected $function;
	protected $bodyCode = array();
	
	public function __construct($function, array $bodyCode) {
		$this->function = $function;
		$this->bodyCode = $bodyCode;
	}
	
	/**
	 * @param \Closure $function
	 */
	public function setFunction($function) {
		$this->function = $function;
	}

	/**
	 * @return null|\Closure
	 */
	public function getFunction() {
		return $this->function;
	}

	/**
	 * @param array $bodyCode
	 */
	public function setBodyCode(array $bodyCode) {
		$this->bodyCode = $bodyCode;
	}
	
	/**
	 * @return array
	 */
	public function getBodyCode() {
		return $this->bodyCode;
	}
}