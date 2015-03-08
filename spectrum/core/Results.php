<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

use spectrum\Exception;

class Results implements ResultsInterface {
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
	 * @param null|bool $result
	 * @param mixed $details Exception object, some string, backtrace info, etc.
	 */
	public function add($result, $details = null) {
		if ($result !== true && $result !== false && $result !== null) {
			throw new Exception('Results is accept only "true", "false" or "null"');
		}
		
		$this->results[] = array(
			'result' => $result,
			'details' => $details,
		);
	}

	/**
	 * @return array
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
			if ($result['result'] === false) {
				return false;
			} else if ($result['result'] === null) {
				$hasNull = true;
			} else if ($result['result'] !== true) {
				throw new Exception('Results should be contain "true", "false" or "null" values only (now it is contain value of "' . gettype($result['result']) . '" type)');
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