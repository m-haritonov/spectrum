<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

use spectrum\core\SpecInterface;

function loadBaseMatchers($storage, SpecInterface $spec)
{
	$matchers = array(
		'eq',
		'false',
		'gt',
		'gte',
		'ident',
		'instanceof',
		'lt',
		'lte',
		'null',
		'throwsException',
		'true',
	);
	
	foreach ($matchers as $matcherName)
	{
		$matcherFileName = __DIR__ . '/../../../matchers/' . $matcherName . '.php';
		$matcherFunctionName = '\spectrum\matchers\\' .$matcherName;
		
		// "instanceof" is reserved word and forbidden to use as function name (but allowed to use as property name)
		if ($matcherName == 'instanceof')
		{
			$matcherFileName = __DIR__ . '/../../../matchers/instanceofMatcher.php';
			$matcherFunctionName = '\spectrum\matchers\instanceofMatcher';
		}
		
		require_once $matcherFileName;
		$spec->matchers->add($matcherName, $matcherFunctionName);
	}
}