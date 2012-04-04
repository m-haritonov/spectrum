<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
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