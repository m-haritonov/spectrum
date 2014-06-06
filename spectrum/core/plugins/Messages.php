<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins;
use spectrum\Exception;

class Messages extends \spectrum\core\plugins\Plugin
{
	protected $messages = array();

	static public function getAccessName()
	{
		return 'messages';
	}
	
	static public function getEventListeners()
	{
		return array(
			array('event' => 'onSpecRunStart', 'method' => 'onSpecRunStart', 'order' => 10),
		);
	}
	
	public function add($message)
	{
		if ($this->getOwnerSpec()->getChildSpecs())
			throw new Exception('Messages::add() method available only on specs without children');
			
		$this->messages[] = $message;
	}

	public function getAll()
	{
		return $this->messages;
	}

	protected function onSpecRunStart()
	{
		$this->messages = array();
	}
}