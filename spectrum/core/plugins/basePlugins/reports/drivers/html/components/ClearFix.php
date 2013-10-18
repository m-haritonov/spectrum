<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components;

class ClearFix extends Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.c-clearFix:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }' . $this->getNewline() .
				$this->getIndention() . 'html.c-browser-ie7 .c-clearFix { zoom: 1; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}
}