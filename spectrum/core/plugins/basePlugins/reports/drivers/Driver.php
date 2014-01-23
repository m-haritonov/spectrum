<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers;

use spectrum\config;
use spectrum\core\plugins\PluginInterface;

abstract class Driver implements DriverInterface
{
	protected $ownerPlugin;
	
	public function __construct(PluginInterface $ownerPlugin)
	{
		$this->ownerPlugin = $ownerPlugin;
	}
	
	public function getOwnerPlugin()
	{
		return $this->ownerPlugin;
	}
	
/**/

	public function getIndention($repeat = 1)
	{
		return str_repeat(config::getOutputIndention(), $repeat);
	}
	
	public function prependIndentionToEachLine($text, $repeat = 1, $trimNewline = true)
	{
		if ($trimNewline)
			$text = $this->trimNewline($text);

		if ($text != '')
			return $this->getIndention($repeat) . str_replace($this->getNewline(), $this->getNewline() . $this->getIndention($repeat), $text);
		else
			return $text;
	}
	
/**/
	
	public function getNewline($repeat = 1)
	{
		return str_repeat(config::getOutputNewline(), $repeat);
	}
	
	public function trimNewline($text)
	{
		$escapedNewline = preg_quote($this->getNewline(), '/');
		return preg_replace('/^(' . $escapedNewline . ')+|(' . $escapedNewline . ')+$/s', '', $text);
	}
	
/**/
	
	public function translate($string, array $replacement = array())
	{
		return strtr($string, $replacement);
	}
}