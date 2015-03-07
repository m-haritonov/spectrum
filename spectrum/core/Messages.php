<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

use spectrum\Exception;

class Messages implements MessagesInterface {
	/**
	 * @var array
	 */
	protected $messages = array();

	/**
	 * @var SpecInterface
	 */
	protected $ownerSpec;
	
	public function __construct(SpecInterface $ownerSpec) {
		$this->ownerSpec = $ownerSpec;
	}
	
	/**
	 * @param string $message
	 */
	public function add($message) {
		if ($this->ownerSpec->getChildSpecs()) {
			throw new Exception('Messages::add() method available only on specs without children');
		}
		
		$this->messages[] = $message;
	}

	/**
	 * @return array
	 */
	public function getAll() {
		return $this->messages;
	}
}