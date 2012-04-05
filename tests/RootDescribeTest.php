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

require_once dirname(__FILE__) . '/init.php';

/*
class \spectrum\RootDescribeTest extends spectrum_BaseAbstract
{
	public function testGetOnceInstance_ShouldBeReturnLastInstance()
	{
		$instance = \spectrum\RootDescribe::getOnceInstance();
		$this->assertSame($instance, \spectrum\RootDescribe::getOnceInstance());
		$this->assertSame($instance, \spectrum\RootDescribe::getOnceInstance());
	}

	public function testCreateOnce_ShouldNotBeCreateNewInstanceIsInstanceAlreadyExists()
	{
		$instance = \spectrum\RootDescribe::getOnceInstance();
		\spectrum\RootDescribe::getOnceInstance();
		$this->assertSame($instance, \spectrum\RootDescribe::getOnceInstance());
	}

	public function testClear_ShouldBeRemoveCurrentInstance()
	{
		$instance = \spectrum\RootDescribe::getOnceInstance();
		\spectrum\RootDescribe::clear();
		$this->assertNotSame($instance, \spectrum\RootDescribe::getOnceInstance());
	}

	public function testShouldBeHaveAllDefaultMatchers()
	{
		$defaultMatchers = $this->getDefaultMatchers();
		$describeMatchers = \spectrum\RootDescribe::getOnceInstance()->getMatchers();
		asort($defaultMatchers);
		asort($describeMatchers);

		$this->assertEquals($defaultMatchers, $describeMatchers);
	}

	protected function getDefaultMatchers()
	{
		$matchers = array();
		$defaultMatchersDir = dirname(__FILE__) . '/../../spectrum/DefaultMatchers/';
		foreach ($this->scanDirRecursive($defaultMatchersDir) as $file)
		{
			if (preg_match('/(abstract\s+)?class\s+([^\s]+)\s/is', file_get_contents($file), $sp))
			{
				$isAbstract = $sp[1];
				$className = $sp[2];
				$matcherName = str_replace('.php', '', basename($file));
				$matcherName{0} = strtolower($matcherName{0});

				if (!$isAbstract)
				{
					$matchers[$matcherName] = new spectrum_Callbacks_Matcher('class:' . trim($className));
				}
			}
		}

		return $matchers;
	}

	protected function scanDirRecursive($dir)
	{
		$lastChar = mb_substr($dir, -1);
		if ($lastChar != '/' && $lastChar != '\\')
			$dir .= '/';

		$files = scandir($dir);

		$resultFiles = array();
		foreach ($files as $file)
		{
			if ($file{0} == '.')
				continue;

			if (is_dir($dir . $file))
				$resultFiles = array_merge($resultFiles, $this->scanDirRecursive($dir . $file));
			else
				$resultFiles[] = $dir . $file;
		}

		return $resultFiles;
	}
}
*/