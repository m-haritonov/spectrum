<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
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