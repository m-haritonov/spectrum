<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

class Results implements ResultsInterface {
	/**
	 * @var ResultInterface[]
	 */
	protected $results = array();

	/**
	 * @var SpecInterface
	 */
	protected $ownerSpec;
	
	public function __construct(SpecInterface $ownerSpec) {
		$this->ownerSpec = $ownerSpec;
	}

	/**
	 * @return SpecInterface
	 */
	public function getOwnerSpec() {
		return $this->ownerSpec;
	}

	/**
	 * @param null|bool $value
	 * @param mixed $details Exception object, some string, backtrace info, etc.
	 */
	public function add($value, $details = null) {
		$resultClass = config::getClassReplacement('\spectrum\core\Result');
		/** @var ResultInterface $result */
		$result = new $resultClass;
		$result->setValue($value);
		$result->setDetails($details);
		$this->results[] = $result;
	}

	/**
	 * @return ResultInterface[]
	 */
	public function getAll() {
		return $this->results;
	}

	/**
	 * @return null|bool
	 */
	public function getTotal() {
		$hasNull = false;
		foreach ($this->results as $result) {
			$value = $result->getValue();
			if ($value === false) {
				return false;
			} else if ($value === null) {
				$hasNull = true;
			}
		}

		if ($hasNull) {
			return null;
		} else if (count($this->results) > 0) {
			return true;
		} else {
			return null;
		}
	}
}