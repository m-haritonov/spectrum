<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets\resultBuffer\details;

class Unknown extends Details
{
	public function getStyles()
	{
		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-resultBuffer-details-unknown {  }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml($details)
	{
		return
			'<div class="g-resultBuffer-details-unknown g-resultBuffer-details">' .
			$this->createWidget('code\Variable')->getHtml($details) .
			'</div>';
	}
}