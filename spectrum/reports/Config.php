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

namespace spectrum\reports;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Config
{
	private static $allowPluginSettingsModify = true;

	private static $locked = false;

	public static function setAllowPluginSettingsModify($isEnable){ return static::setConfigValue(static::$allowPluginSettingsModify, $isEnable); }
	public static function getAllowPluginSettingsModify(){ return static::$allowPluginSettingsModify; }

	public static function lock(){ static::$locked = true; }
	public static function isLocked(){ return static::$locked; }

	private static function setConfigClassValue(&$var, $className, $requiredInterface = null)
	{
		if (!class_exists($className))
			throw new Exception('Class "' . $className . '" not exists');
		else if ($requiredInterface != null)
		{
			$reflection = new \ReflectionClass($className);
			if (!$reflection->implementsInterface($requiredInterface))
				throw new Exception('Class "' . $className . '" should be implement interface "' . $requiredInterface . '"');
		}

		return static::setConfigValue($var, $className);
	}

	private static function setConfigValue(&$var, $value)
	{
		if (static::$locked)
			throw new Exception('spectrum\core\Config is locked');

		$var = $value;
	}
}