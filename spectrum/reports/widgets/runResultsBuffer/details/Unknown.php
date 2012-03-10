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

namespace net\mkharitonov\spectrum\reports\widgets\runResultsBuffer\details;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
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
			'<div class="g-runResultsBuffer-details g-runResultsBuffer-details-unknown">' . $this->getNewline() .
				$this->getIndention() . $this->codeWidget->getHtmlForVariable($details) . $this->getNewline() .
			'</div>' . $this->getNewline();
	}
}