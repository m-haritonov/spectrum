<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components;

use spectrum\core\plugins\basePlugins\reports\drivers\DriverInterface;

class Component
{
	protected $ownerDriver;

	public function __construct(DriverInterface $ownerDriver)
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

	protected function createComponent()
	{
		return call_user_func_array(array($this->ownerDriver, 'createComponent'), func_get_args());
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
		return htmlspecialchars($this->ownerDriver->translate($string, $replacement));
	}
}