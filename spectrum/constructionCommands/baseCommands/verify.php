<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;

/**
 * @param mixed $value1
 * @param string $operator One of: '==', '===', '!=', '<>', '!==', '<', '>', '<=', '>=', 'instanceof', '!instanceof'
 * @param mixed $value2
 * @throws \spectrum\constructionCommands\Exception If called not at running state
 */
function verify($value1, $operator = null, $value2 = null)
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "verify" should be call only at running state');
	
	$args = func_get_args();
	$reflection = new \ReflectionClass(\spectrum\core\Config::getVerificationClass());
	$verify = $reflection->newInstanceArgs($args);
	return $verify;
}