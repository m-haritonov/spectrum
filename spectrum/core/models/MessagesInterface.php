<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\models;

interface MessagesInterface {
	public function __construct(SpecInterface $ownerSpec);
	
	/**
	 * @param string $message
	 */
	public function add($message);

	/**
	 * @return array
	 */
	public function getAll();
}