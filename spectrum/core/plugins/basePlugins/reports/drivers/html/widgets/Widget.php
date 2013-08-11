<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\widgets;

class Widget
{
	protected $ownerDriver;

	public function __construct(\spectrum\core\plugins\basePlugins\reports\drivers\html\Html $ownerDriver)
	{
		$this->ownerDriver = $ownerDriver;
	}

	public function getStyles()
	{
		return null;
	}

	public function getScripts()
	{
		return null;
	}

	protected function getOwnerDriver()
	{
		return $this->ownerDriver;
	}
	
	protected function createWidget($name/*, ... */)
	{
		return call_user_func_array(array($this->ownerDriver, 'createWidget'), func_get_args());
	}
	
	protected function getIndention($repeat = 1)
	{
		return $this->ownerDriver->getIndention($repeat);
	}

	protected function prependIndentionToEachLine($text, $repeat = 1, $trimNewline = true)
	{
		return $this->ownerDriver->prependIndentionToEachLine($text, $repeat, $trimNewline);
	}

	protected function getNewline($repeat = 1)
	{
		return $this->ownerDriver->getNewline($repeat);
	}

	protected function trimNewline($text)
	{
		return $this->ownerDriver->trimNewline($text);
	}
	
	protected function translate($string, array $replacement = array())
	{
		$string = $this->ownerDriver->translate($string, $replacement);
		return htmlspecialchars($string);
	}
}