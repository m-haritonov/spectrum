<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets;

interface WidgetInterface
{
	public function __construct(\spectrum\reports\Plugin $ownerPlugin);
	public function getOwnerPlugin();
	public function getStyles();
	public function getScripts();
}