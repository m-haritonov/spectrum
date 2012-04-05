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

namespace spectrum\core\testEnv;

class PluginStub extends \spectrum\core\plugins\Plugin
{
	static private $activationsCount = 0;
	static private $lastInstance;

	static public function getActivationsCount()
	{
		return static::$activationsCount;
	}

	static public function getLastInstance()
	{
		return static::$lastInstance;
	}

	static public function reset()
	{
		static::$activationsCount = 0;
		static::$lastInstance = null;
	}

	public function __construct(\spectrum\core\SpecInterface $ownerSpec, $accessName)
	{
		parent::__construct($ownerSpec, $accessName);
		static::$activationsCount++;
		static::$lastInstance = $this;
	}
}