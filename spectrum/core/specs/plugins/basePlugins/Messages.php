<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs\plugins\basePlugins;
use spectrum\core\specs\plugins\Exception;

class Messages extends \spectrum\core\specs\plugins\Plugin
{
	protected $messages = array();

	static public function getAccessName()
	{
		return 'messages';
	}
	
	static public function getEventListeners()
	{
		return array(
			array('event' => 'onSpecRunBefore', 'method' => 'onSpecRunBefore', 'order' => 20),
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

	protected function onSpecRunBefore()
	{
		$this->messages = array();
	}
}