<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\text;

use spectrum\core\plugins\basePlugins\reports\drivers\Driver;

class Text extends Driver
{
	public function createComponent($name)
	{
		throw new \spectrum\core\plugins\basePlugins\reports\Exception('Driver is under development');
	}
	
	public function getContentBeforeSpec()
	{
		return 'Driver is under development';
	}

	public function getContentAfterSpec()
	{
		return 'Driver is under development';
	}
}