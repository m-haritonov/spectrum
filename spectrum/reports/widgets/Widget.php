<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets;

class Widget implements WidgetInterface
{
	protected $ownerPlugin;

	public function __construct(\spectrum\reports\Plugin $ownerPlugin)
	{
		$this->ownerPlugin = $ownerPlugin;
	}

	public function getOwnerPlugin()
	{
		return $this->ownerPlugin;
	}

	public function getStyles()
	{
		return null;
	}

	public function getScripts()
	{
		return null;
	}

	protected function translate($string, array $replacement = array())
	{
		$string = $this->getOwnerPlugin()->translate($string, $replacement);
		return htmlspecialchars($string);
	}

	protected function getIndention($repeat = 1)
	{
		return $this->getOwnerPlugin()->getIndention($repeat);
	}

	protected function prependIndentionToEachLine($text, $repeat = 1, $trimNewline = true)
	{
		return $this->getOwnerPlugin()->prependIndentionToEachLine($text, $repeat, $trimNewline);
	}

	protected function getNewline($repeat = 1)
	{
		return $this->getOwnerPlugin()->getNewline($repeat);
	}

	protected function trimNewline($text)
	{
		return $this->getOwnerPlugin()->trimNewline($text);
	}
}