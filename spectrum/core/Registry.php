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

namespace spectrum\core;

class Registry
{
	/**
	 * @var \spectrum\core\SpecItemInterface
	 */
	static protected $runningSpecItem;

	/**
	 * @var \spectrum\core\SpecContainerInterface
	 */
	static protected $runningSpecContainer;

	static public function getRunningSpecItem()
	{
		return static::$runningSpecItem;
	}

	static public function setRunningSpecItem(SpecItemInterface $instance = null)
	{
		if ($instance && !$instance->isRunning())
			throw new Exception('Method "' . __METHOD__ . '" should be accept only running specs');

		static::$runningSpecItem = $instance;
	}

	static public function getRunningSpecContainer()
	{
		return static::$runningSpecContainer;
	}

	static public function setRunningSpecContainer(SpecContainerInterface $instance = null)
	{
		if ($instance && !$instance->isRunning())
			throw new Exception('Method "' . __METHOD__ . '" should be accept only running specs');

		static::$runningSpecContainer = $instance;
	}
}