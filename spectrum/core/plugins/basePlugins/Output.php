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
class Output extends \spectrum\core\plugins\Plugin
{
	static public function getAccessName()
	{
		return 'output';
	}
	
	public function put($string, $inputCharset = null)
	{
		print $this->convertToOutputCharset($string, $inputCharset);
	}

	public function convertToOutputCharset($string, $inputCharset = null)
	{
		if ($inputCharset === null)
			$inputCharset = $this->getOwnerSpec()->getInputCharset();
		
		$outputCharset = config::getOutputCharset();

		if (mb_strtolower($inputCharset) === mb_strtolower($outputCharset))
			return $string;
		else
			return iconv($inputCharset, $outputCharset, $string);
	}
}