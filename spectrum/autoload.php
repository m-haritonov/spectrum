<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

spl_autoload_register(function($class)
{
	$baseNamespace = 'spectrum\\';
	if (mb_stripos($class, $baseNamespace) === 0)
	{
		$file = mb_substr($class, mb_strlen($baseNamespace));
		$file = str_replace('\\', '/', $file);
		$file = __DIR__ . '/' . $file . '.php';

		if (file_exists($file))
			require_once $file;
	}
});