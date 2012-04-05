<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets;

class Clearfix extends \spectrum\reports\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-clearfix:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }' . $this->getNewline() .
				$this->getIndention() . 'body.g-browser-ie7 .g-clearfix { zoom: 1; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}
}