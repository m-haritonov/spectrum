<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\_private;

use spectrum\tests\automatic\Test;

require_once __DIR__ . '/../../../init.php';

class AutoloadTest extends Test {
	public function testNames_EntityNamesIsIdenticalToFileNames() {
		foreach (\spectrum\tests\_testware\tools::getDirectoryFilesRecursively(array(__DIR__ . '/../../../../spectrum', __DIR__ . '/../../../_testware', __DIR__ . '/../../../automatic')) as $file) {
			if (mb_substr($file, -4, mb_strlen($file, 'us-ascii'), 'us-ascii') === '.php') {
				require_once $file;
			}
		}
		
		$foundEntitiesCount = 0;
		foreach (\spectrum\tests\_testware\tools::getDeclaredEntities() as $entity) {
			if (mb_stripos($entity['name'], 'spectrum\\', null, 'us-ascii') === 0 && mb_stripos($entity['name'], 'spectrum\tests\_testware\_dynamicClasses_\\', null, 'us-ascii') !== 0) {
				$foundEntitiesCount++;
				
				if ((string) $entity['type'] === 'function') {
					$reflection = new \ReflectionFunction($entity['name']);
				} else {
					$reflection = new \ReflectionClass($entity['name']);
				}
				
				$originalName = $reflection->getName();
				
				$originalFileName = $reflection->getFileName();
				$originalFileName = str_replace('/', '\\', $originalFileName);
				$originalFileName = mb_substr($originalFileName, 0, -4, 'us-ascii'); // Remove file extension
				$originalFileName = mb_substr($originalFileName, mb_strlen(dirname(dirname(dirname(dirname(__DIR__)))), 'us-ascii') + 1, mb_strlen($originalFileName, 'us-ascii'), 'us-ascii'); // Remove directory prefix
				// Add prefix for files in "tests" directory
				if (mb_stripos($originalFileName, 'spectrum\\', null, 'us-ascii') !== 0) {
					$originalFileName = 'spectrum\\' . $originalFileName;
				}
				
				$this->assertSame($originalName, $originalFileName);
			}
		}
		
		$this->assertGreaterThan(90, $foundEntitiesCount);
	}
}