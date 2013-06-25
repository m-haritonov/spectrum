<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs\plugins\basePlugins;
use spectrum\config;
use spectrum\core\specs\plugins\Exception;

/**
 * Use this plugin to output current owner spec data (or get spec data in correct output encoding). Do not use plugin
 * from one spec instance to output another spec instance data.
 */
class Output extends \spectrum\core\specs\plugins\Plugin
{
	protected $inputEncoding;
	protected $outputEncoding;

	static protected $defaultInputEncoding = 'utf-8';
	static protected $defaultOutputEncoding = 'utf-8';

	/**
	 * For more performance
	 * @var bool
	 */
	static protected $isEncodingChanged = false;

	static public function getAccessName()
	{
		return 'output';
	}
	
/**/

	public function setInputEncoding($encoding)
	{
		$this->handleModifyDeny();
		
		if (!config::getAllowInputEncodingModify())
			throw new Exception('Input encoding modify deny in config');

		$this->inputEncoding = $encoding;
		static::$isEncodingChanged = true;
	}

	public function getInputEncoding()
	{
		return $this->inputEncoding;
	}

	public function getInputEncodingThroughRunningAncestors()
	{
		if (static::$isEncodingChanged)
			return $this->callMethodThroughRunningAncestorSpecs('getInputEncoding', array(), static::$defaultInputEncoding);
		else
			return static::$defaultInputEncoding;
	}

/**/

	public function setOutputEncoding($encoding)
	{
		$this->handleModifyDeny();
		
		if (!config::getAllowOutputEncodingModify())
			throw new Exception('Output encoding modify deny in config');

		$this->outputEncoding = $encoding;
		static::$isEncodingChanged = true;
	}

	public function getOutputEncoding()
	{
		return $this->outputEncoding;
	}

	public function getOutputEncodingThroughRunningAncestors()
	{
		if (static::$isEncodingChanged)
			return $this->callMethodThroughRunningAncestorSpecs('getOutputEncoding', array(), static::$defaultOutputEncoding);
		else
			return static::$defaultOutputEncoding;
	}

/**/

	public function put($string)
	{
		print $this->convertToOutputEncoding($string);
	}

	public function convertToOutputEncoding($string)
	{
		$inputEncoding = $this->getInputEncodingThroughRunningAncestors();
		$outputEncoding = $this->getOutputEncodingThroughRunningAncestors();

		if (mb_strtolower($inputEncoding) == mb_strtolower($outputEncoding))
			return $string;
		else
			return iconv($inputEncoding, $outputEncoding, $string);
	}
}