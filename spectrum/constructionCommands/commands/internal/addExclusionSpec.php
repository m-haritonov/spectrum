<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\constructionCommands\commands\internal;

use spectrum\core\SpecInterface;

function addExclusionSpec($storage, SpecInterface $spec)
{
	if (!@$storage['_self_']['exclusionSpecs'])
		$storage['_self_']['exclusionSpecs'] = array();
	
	$storage['_self_']['exclusionSpecs'][] = $spec;
}