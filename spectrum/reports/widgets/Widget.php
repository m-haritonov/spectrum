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

namespace spectrum\reports\widgets;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
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