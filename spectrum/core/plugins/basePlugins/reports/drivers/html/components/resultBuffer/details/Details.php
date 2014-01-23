<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\resultBuffer\details;

abstract class Details extends \spectrum\core\plugins\basePlugins\reports\drivers\html\components\Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer-details { padding: 7px; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}
}