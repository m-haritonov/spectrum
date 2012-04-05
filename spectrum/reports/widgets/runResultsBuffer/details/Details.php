<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
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