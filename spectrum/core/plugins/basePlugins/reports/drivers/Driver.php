<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers;

abstract class Driver implements DriverInterface
{
	protected $ownerPlugin;
	
	public function __construct(\spectrum\core\plugins\basePlugins\reports\Reports $ownerPlugin)
	{
		$this->ownerPlugin = $ownerPlugin;
	}
	
	public function getOwnerPlugin()
	{
		return $this->ownerPlugin;
	}
	
	public function getIndention($repeat = 1)
	{
		return str_repeat($this->ownerPlugin->getNewline(), $repeat);
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
	
	public function getNewline($repeat = 1)
	{
		return str_repeat($this->ownerPlugin->getNewline(), $repeat);
	}
	
	public function trimNewline($text)
	{
		$escapedNewline = preg_quote($this->getNewline(), '/');
		return preg_replace('/^(' . $escapedNewline . ')+|(' . $escapedNewline . ')+$/s', '', $text);
	}
	
	/**
	 * @param $string
	 * @param array $replacement
	 * @return string
	 */
	public function translate($string, array $replacement = array())
	{
		return strtr($string, $replacement);
	}
}