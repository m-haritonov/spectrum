<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\text;

use spectrum\core\SpecInterface;

class text
{
	static public function getContentBeforeSpec(SpecInterface $spec)
	{
		return 'Driver is under development';
	}

	static public function getContentAfterSpec(SpecInterface $spec)
	{
		return 'Driver is under development';
	}
}