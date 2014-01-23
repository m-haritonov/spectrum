<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins;
use spectrum\config;
use spectrum\core\plugins\Exception;

/**
 * Use this plugin to output current owner spec data (or get spec data in correct output charset). Do not use plugin
 * from one spec instance to output another spec instance data.
 */
class Charset extends \spectrum\core\plugins\Plugin
{
	protected $inputCharset;

	/**
	 * For more performance
	 * @var bool
	 */
	static protected $isInputCharsetChanged = false;

	static public function getAccessName()
	{
		return 'charset';
	}
	
	public function setInputCharset($charsetName)
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!config::getAllowInputCharsetModify())
			throw new Exception('Input charset modify deny in config');

		$this->inputCharset = $charsetName;
		static::$isInputCharsetChanged = true;
	}

	public function getInputCharset()
	{
		return $this->inputCharset;
	}

	public function getInputCharsetThroughRunningAncestors()
	{
		$defaultCharset = 'utf-8';
		
		if (static::$isInputCharsetChanged)
			return $this->callMethodThroughRunningAncestorSpecs('getInputCharset', array(), $defaultCharset);
		else
			return $defaultCharset;
	}
}