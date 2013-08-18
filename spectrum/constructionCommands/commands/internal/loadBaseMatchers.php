<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;
use spectrum\config;
use spectrum\core\SpecInterface;

function loadBaseMatchers(SpecInterface $spec)
{
	if (config::getAllowBaseMatchersOverride())
		$allowOverride = true;
	else
		$allowOverride = false;
	
	$matchers = array(
		'eq',
		'false',
		'gt',
		'gte',
		'ident',
		'lt',
		'lte',
		'null',
		'throwsException',
		'true',
	);
	
	foreach ($matchers as $matcherName)
	{
		require_once __DIR__ . '/../../../matchers/' . $matcherName . '.php';
		$spec->matchers->add($matcherName, '\spectrum\matchers\\' .$matcherName, $allowOverride);
	}
	
	require_once __DIR__ . '/../../../matchers/instanceofMatcher.php';
	$spec->matchers->add('instanceof', '\spectrum\matchers\instanceofMatcher', $allowOverride);
}