<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers;

use spectrum\core\plugins\PluginInterface;

interface DriverInterface
{
	public function __construct(PluginInterface $ownerPlugin);
	public function getOwnerPlugin();
	public function getContentBeforeSpec();
	public function getContentAfterSpec();
	public function createWidget($name);
	public function getIndention($repeat = 1);
	public function prependIndentionToEachLine($text, $repeat = 1, $trimNewline = true);
	public function getNewline($repeat = 1);
	public function trimNewline($text);
	public function translate($string, array $replacement = array());
}