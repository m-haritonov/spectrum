<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;

class Messages extends \spectrum\core\plugins\Plugin
{
	protected $messages = array();

	public function add($message)
	{
		$this->messages[] = $message;
	}

	public function getAll()
	{
		return $this->messages;
	}

	public function clear()
	{
		$this->messages = array();
	}
}