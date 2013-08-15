<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\testHelpers\agents\core;

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

	public function __construct(\spectrum\core\specs\SpecInterface $ownerSpec, $accessName)
	{
		parent::__construct($ownerSpec, $accessName);
		static::$activationsCount++;
		static::$lastInstance = $this;
	}
}