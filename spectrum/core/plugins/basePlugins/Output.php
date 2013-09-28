<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;
use spectrum\config;
use spectrum\core\plugins\Exception;

/**
 * Use this plugin to output current owner spec data (or get spec data in correct output encoding). Do not use plugin
 * from one spec instance to output another spec instance data.
 */
class Output extends \spectrum\core\plugins\Plugin
{
	protected $inputEncoding;
	protected $outputEncoding;

	/**
	 * For more performance
	 * @var bool
	 */
	static protected $isInputEncodingChanged = false;
	static protected $isOutputEncodingChanged = false;

	static public function getAccessName()
	{
		return 'output';
	}
	
/**/

	public function setInputEncoding($encoding)
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!config::getAllowInputEncodingModify())
			throw new Exception('Input encoding modify deny in config');

		$this->inputEncoding = $encoding;
		static::$isInputEncodingChanged = true;
	}

	public function getInputEncoding()
	{
		return $this->inputEncoding;
	}

	public function getInputEncodingThroughRunningAncestors()
	{
		$defaultEncoding = 'utf-8';
		
		if (static::$isInputEncodingChanged)
			return $this->callMethodThroughRunningAncestorSpecs('getInputEncoding', array(), $defaultEncoding);
		else
			return $defaultEncoding;
	}

/**/

	public function setOutputEncoding($encoding)
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!config::getAllowOutputEncodingModify())
			throw new Exception('Output encoding modify deny in config');

		$this->outputEncoding = $encoding;
		static::$isOutputEncodingChanged = true;
	}

	public function getOutputEncoding()
	{
		return $this->outputEncoding;
	}

	public function getOutputEncodingThroughRunningAncestors()
	{
		$defaultEncoding = 'utf-8';
		
		if (static::$isOutputEncodingChanged)
			return $this->callMethodThroughRunningAncestorSpecs('getOutputEncoding', array(), $defaultEncoding);
		else
			return $defaultEncoding;
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

		if (mb_strtolower($inputEncoding) === mb_strtolower($outputEncoding))
			return $string;
		else
			return iconv($inputEncoding, $outputEncoding, $string);
	}
}