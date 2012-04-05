<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets\runResultsBuffer\details;

class Unknown extends Details
{
	public function getStyles()
	{
		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer-details-unknown {  }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml($details)
	{
		return
			'<div class="g-runResultsBuffer-details-unknown g-runResultsBuffer-details">' .
			$this->getOwnerPlugin()->createWidget('code\Variable')->getHtml($details) .
			'</div>';
	}
}