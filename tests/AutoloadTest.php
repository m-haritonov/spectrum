<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests;

require_once __DIR__ . '/init.php';

class AutoloadTest extends Test
{
	public function testLoadsFileForCalledClass()
	{
		$this->assertFalse(class_exists('\spectrum\core\Spec', false));
		new \spectrum\core\Spec();
		$this->assertTrue(class_exists('\spectrum\core\Spec', false));
	}

	public function testNames_EntityNamesIsIdenticalToFileNames()
	{
		foreach ($this->getDirectoryFilesRecursively(array(__DIR__ . '/../spectrum', __DIR__ . '/../tests')) as $file)
		{
			if (mb_substr($file, -4) === '.php')
				require_once $file;
		}
		
		$foundEntitiesCount = 0;
		foreach ($this->getDeclaredEntities() as $entity)
		{
			if (mb_stripos($entity['name'], 'spectrum\\') === 0 && mb_stripos($entity['name'], 'spectrum\tests\testware\_dynamicClasses_\\') !== 0)
			{
				$foundEntitiesCount++;
				
				if ($entity['type'] == 'function')
					$reflection = new \ReflectionFunction($entity['name']);
				else
					$reflection = new \ReflectionClass($entity['name']);
				
				$originalName = $reflection->getName();
				
				$originalFileName = $reflection->getFileName();
				$originalFileName = str_replace('/', '\\', $originalFileName);
				$originalFileName = mb_substr($originalFileName, 0, -4); // Remove file extension
				$originalFileName = mb_substr($originalFileName, mb_strlen(__DIR__) - mb_strlen(basename(__DIR__)));
				// Add prefix for files in "tests" directory
				if (mb_stripos($originalFileName, 'spectrum\\') !== 0)
					$originalFileName = 'spectrum\\' . $originalFileName;
				
				$this->assertSame($originalName, $originalFileName);
			}
		}
		
		$this->assertGreaterThan(90, $foundEntitiesCount);
	}
	
	private function getDeclaredEntities()
	{
		$result = array();
		
		$declaredFunctions = get_defined_functions();
		foreach ($declaredFunctions['user'] as $name)
			$result[] = array('name' => $name, 'type' => 'function');
		
		foreach (get_declared_interfaces() as $name)
			$result[] = array('name' => $name, 'type' => 'interface');
		
		foreach (get_declared_classes() as $name)
			$result[] = array('name' => $name, 'type' => 'class');
		
		if (function_exists('get_declared_traits'))
		{
			foreach (get_declared_traits() as $name)
				$result[] = array('name' => $name, 'type' => 'trait');
		}
		
		return $result;
	}
	
	private function getDirectoryFilesRecursively($paths)
	{
		$resultFiles = array();
		
		if (is_array($paths))
		{
			foreach ($paths as $path)
				$resultFiles = array_merge($resultFiles, $this->getDirectoryFilesRecursively($path));
		}
		else
		{
			foreach (scandir($paths) as $file)
			{
				if (mb_substr($file, 0, 1) == '.')
					continue;

				$file = $paths . '/' . $file;

				if (is_dir($file))
					$resultFiles = array_merge($resultFiles, $this->getDirectoryFilesRecursively($file));
				else
					$resultFiles[] = $file;
			}
		}
		
		return $resultFiles;
	}
}