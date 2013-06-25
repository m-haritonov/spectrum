<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets;

class ClearFix extends \spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-clearFix:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }' . $this->getNewline() .
				$this->getIndention() . 'body.g-browser-ie7 .g-clearFix { zoom: 1; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}
}