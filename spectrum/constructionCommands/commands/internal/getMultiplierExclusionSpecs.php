<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

function getMultiplierExclusionSpecs()
{
	$reflection = new \ReflectionFunction('\spectrum\constructionCommands\commands\internal\addMultiplierExclusionSpec');
	$vars = $reflection->getStaticVariables();
	if ($vars['exclusionSpecs'])
		return $vars['exclusionSpecs'];
	else
		return array();
}