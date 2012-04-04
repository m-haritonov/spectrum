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

namespace spectrum\core\plugins;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
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