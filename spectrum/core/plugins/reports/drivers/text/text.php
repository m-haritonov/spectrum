<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text;

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