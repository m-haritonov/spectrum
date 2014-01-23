<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\constructionCommands\commands\internal;

function getNameForArguments($storage, array $arguments, $defaultName)
{
	$arguments = array_values($arguments);
	
	if (count($arguments) >= 1 && is_scalar($arguments[0]) && !is_string($defaultName))
	{
		if (mb_strlen($arguments[0]) > 100)
			return mb_substr($arguments[0], 0, 100) . '...';
		else
			return $arguments[0];
	}
	else
		return $defaultName;
}