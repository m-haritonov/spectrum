<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic;

require_once __DIR__ . '/../init.php';

abstract class Test extends \PHPUnit_Framework_TestCase {
	protected function setUp() {
		parent::setUp();
		
		\spectrum\_private\setCurrentBuildingSpec(null);
		
		\spectrum\tests\_testware\tools::backupObjectProperties(\spectrum\_private\getRootSpec());
		\spectrum\tests\_testware\tools::backupClassStaticProperties('\spectrum\config');
		\spectrum\tests\_testware\tools::backupClassStaticProperties('\spectrum\_private\reports\html\components\specList');
		\spectrum\tests\_testware\tools::backupClassStaticProperties('\spectrum\_private\reports\html\components\code\variable');
		\spectrum\tests\_testware\tools::backupClassStaticProperties('\spectrum\_private\reports\text\components\specList');
		\spectrum\tests\_testware\tools::backupClassStaticProperties('\spectrum\_private\reports\text\components\code\variable');
		
		\spectrum\tests\_testware\tools::$temp = null;
	}
	
	protected function tearDown() {
		\spectrum\tests\_testware\tools::restoreClassStaticProperties('\spectrum\_private\reports\text\components\code\variable');
		\spectrum\tests\_testware\tools::restoreClassStaticProperties('\spectrum\_private\reports\text\components\specList');
		\spectrum\tests\_testware\tools::restoreClassStaticProperties('\spectrum\_private\reports\html\components\code\variable');
		\spectrum\tests\_testware\tools::restoreClassStaticProperties('\spectrum\_private\reports\html\components\specList');
		\spectrum\tests\_testware\tools::restoreClassStaticProperties('\spectrum\config');
		\spectrum\tests\_testware\tools::restoreObjectProperties(\spectrum\_private\getRootSpec());

		parent::tearDown();
	}

	/**
	 * @param null|\Closure $callback
	 */
	final protected function assertThrowsException($expectedClass, $stringInMessageOrCallback, $callback = null) {
		if ($callback === null) {
			$message = null;
			$callback = $stringInMessageOrCallback;
		} else {
			$message = $stringInMessageOrCallback;
		}

		try {
			$callback();
		} catch (\Exception $e) {
			$actualClass = '\\' . get_class($e);
			// Class found
			if ((string) $actualClass === (string) $expectedClass || is_subclass_of($actualClass, $expectedClass)) {
				if ($message !== null) {
					$this->assertSame($message, $e->getMessage());
				}
				
				return null;
			}
			
			throw $e;
		}

		$this->fail('Exception "' . $expectedClass . '" not thrown');
	}
	
	final protected function getProviderWithCorrectArgumentsForGroupAndTestBuilders($name = null, $contexts = null, $body = null, $settings = null) {
		$values = array(
			'name' => array('some name text', 123),
			'contexts' => array(array(), array('aaa' => array('bbb', 'ccc')), function(){}, function(){
				\spectrum\group(null, null, function(){}, null);
				\spectrum\test(null, null, function(){}, null);
			}),
			'body' => array(function(){}, function(){
				\spectrum\group(null, null, function(){}, null);
				\spectrum\test(null, null, function(){}, null);
			}),
			'settings' => array(true, false, 8, array(), array('breakOnFirstPhpError' => false), array(
				'catchPhpErrors' => 8,
				'breakOnFirstPhpError' => true,
				'breakOnFirstMatcherFail' => true,
			)),
		);
		
		$patterns = array(
			array('body'),
			array('body', 'settings'),
			
			array('contexts', 'body'),
			array('contexts', 'body', 'settings'),
			
			array('name', 'body'),
			array('name', 'body', 'settings'),
			
			array('name', 'contexts', 'body'),
			array('name', 'contexts', 'body', 'settings'),
			
			array(null, 'body', null),
			array(null, 'body', 'settings'),
			
			array(null, null, 'body', null),
			array(null, null, 'body', 'settings'),
			array(null, 'contexts', 'body', null),
			array(null, 'contexts', 'body', 'settings'),
			array('name', null, 'body', null),
			array('name', null, 'body', 'settings'),
			array('name', 'contexts', 'body', null),
		);
		
		$rows = array();
		foreach ($values['name'] as $valueOfName) {
			foreach ($values['contexts'] as $valueOfContexts) {
				foreach ($values['body'] as $valueOfBody) {
					foreach ($values['settings'] as $valueOfSettings) {
						foreach ($patterns as $pattern) {
							if ($name !== null) {
								if (in_array('name', $pattern)) {
									$valueOfName = $name;
								} else {
									continue;
								}
							}
							
							if ($contexts !== null) {
								if (in_array('contexts', $pattern)) {
									$valueOfContexts = $contexts;
								} else {
									continue;
								}
							}
							
							if ($body !== null) {
								if (in_array('body', $pattern)) {
									$valueOfBody = $body;
								} else {
									continue;
								}
							}
							
							if ($settings !== null) {
								if (in_array('settings', $pattern)) {
									$valueOfSettings = $settings;
								} else {
									continue;
								}
							}
							
							$row = $pattern;
							
							$key = array_search('name', $pattern, true);
							if ($key !== false) {
								$row[$key] = $valueOfName;
							}
								
							$key = array_search('contexts', $pattern, true);
							if ($key !== false) {
								$row[$key] = $valueOfContexts;
							}
								
							$key = array_search('body', $pattern, true);
							if ($key !== false) {
								$row[$key] = $valueOfBody;
							}
								
							$key = array_search('settings', $pattern, true);
							if ($key !== false) {
								$row[$key] = $valueOfSettings;
							}
							
							$row = array($row);
							if (!in_array($row, $rows, true)) {
								$rows[] = $row;
							}
						}
					}
				}
			}
		}
		
		return $rows;
	}
}