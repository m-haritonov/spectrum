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

namespace spectrum\reports\widgets\runResultsBuffer\details;
use \spectrum\core\asserts\MatcherCallDetailsInterface;

abstract class Details extends \spectrum\reports\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer-details { padding: 7px; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}
}