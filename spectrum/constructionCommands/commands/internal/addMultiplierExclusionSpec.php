<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;
use spectrum\core\SpecInterface;

function addMultiplierExclusionSpec(SpecInterface $spec = null)
{
	static $exclusionSpecs = array();
	$exclusionSpecs[] = $spec;
}