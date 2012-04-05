<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

spl_autoload_register(function($class)
{
	$rootNamespace = 'spectrum\\';
	if (mb_strpos($class, $rootNamespace) === 0)
	{
		$file = $class;
		$file = str_replace($rootNamespace, '', $file);
		$file = str_replace('\\', '/', $file);
		$file = dirname(__FILE__) . '/' . $file . '.php';

		if (file_exists($file))
			require_once $file;
	}
});