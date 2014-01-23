<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\constructionCommands\commands\internal;

function getExclusionSpecs($storage)
{
	if (@$storage['internal_addExclusionSpec']['exclusionSpecs'])
		return $storage['internal_addExclusionSpec']['exclusionSpecs'];
	else
		return array();
}