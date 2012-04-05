<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins;

class Plugin implements PluginInterface
{
	/** @var \spectrum\core\Spec */
	protected $ownerSpec;
	protected $accessName;

	public function __construct(\spectrum\core\SpecInterface $ownerSpec, $accessName)
	{
		$this->ownerSpec = $ownerSpec;
		$this->accessName = $accessName;
	}

	public function getOwnerSpec()
	{
		return $this->ownerSpec;
	}

	public function getAccessName()
	{
		return $this->accessName;
	}

	protected function callCascadeThroughRunningContexts($methodName, $args = array(), $defaultReturnValue = null, $emptyReturnValue = null)
	{
		$stack = $this->getOwnerSpec()->selector->getAncestorsWithRunningContextsStack();
		$stack[] = $this->getOwnerSpec();
		$stack = array_reverse($stack);

		$accessName = $this->accessName;
		foreach ($stack as $spec)
		{
			$return = call_user_func_array(array($spec->$accessName, $methodName), $args);
			if ($return !== $emptyReturnValue)
				return $return;
		}

		return $defaultReturnValue;
	}
}