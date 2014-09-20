<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

spl_autoload_register(function($class) {
	$baseNamespace = 'spectrum\\tests\\';
	if (mb_stripos($class, $baseNamespace, null, 'us-ascii') === 0) {
		$file = mb_substr($class, mb_strlen($baseNamespace, 'us-ascii'), mb_strlen($class, 'us-ascii'), 'us-ascii');
		$file = str_replace('\\', '/', $file);
		$file = __DIR__ . '/' . $file . '.php';

		if (file_exists($file)) {
			require_once $file;
		}
	}
});