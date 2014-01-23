<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\resultBuffer\details;

class Unknown extends Details
{
	public function getStyles()
	{
		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer-details-unknown {}' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml($details)
	{
		return
			'<div class="c-resultBuffer-details-unknown c-resultBuffer-details">' .
				$this->createComponent('code\Variable')->getHtml($details) .
			'</div>';
	}
}