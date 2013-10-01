<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands;

use spectrum\config;

/**
 * @method addMatcher($name, $function)
 * @method after($function)
 * @method be($testedValue)
 * @method before($function)
 * @method fail($message = null, $code = 0)
 * @method group($name = null, $contexts = null, $body = null, $settings = null)
 * @method message($message)
 * @method test($name = null, $contexts = null, $body = null, $settings = null)
 * @method this()
 * @method internal_addExclusionSpec(\spectrum\core\SpecInterface $spec = null)
 * @method internal_callFunctionOnDeclaringSpec($function, \spectrum\core\SpecInterface $spec)
 * @method internal_convertContextArrayToSpecs(array $contexts)
 * @method internal_filterOutExclusionSpecs(array $specs)
 * @method internal_getArgumentsForSpecDeclaringCommand(array $arguments)
 * @method internal_getCurrentDeclaringSpec()
 * @method internal_getRunningEndingSpec()
 * @method internal_getExclusionSpecs()
 * @method internal_getNameForArguments(array $arguments, $defaultName)
 * @method internal_getRootSpec()
 * @method internal_isRunningState()
 * @method internal_loadBaseMatchers(\spectrum\core\SpecInterface $spec)
 * @method internal_setCurrentDeclaringSpec(\spectrum\core\SpecInterface $spec = null)
 * @method internal_setSpecSettings(\spectrum\core\SpecInterface $spec, $settings)
 */
final class callBroker implements callBrokerInterface
{
	// For abstract class imitation (using "abstract" keyword with "final" not allowed)
	private function __construct(){}
	
	static private $storage = array('_self_' => null);
	
	static public function __callStatic($constructionCommandName, array $arguments = array())
	{
		if (!config::isLocked())
			config::lock();
		
		if (!array_key_exists($constructionCommandName, self::$storage))
			self::$storage[$constructionCommandName] = array();
		
		array_unshift($arguments, null);
		$storageCopy = self::$storage;
		$arguments[0] = &$storageCopy; // Create reference to copy for "function(&$storage)" expression support
		$arguments[0]['_self_'] = &self::$storage[$constructionCommandName];
		
		return call_user_func_array(\spectrum\config::getRegisteredConstructionCommandFunction($constructionCommandName), $arguments);
	}
}