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

namespace spectrum\core\testEnv;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class SpecContainerDescribeMock extends \spectrum\core\SpecContainerDescribe
{
	private $__injectionsToRun = array();
	private $__injectionsToRunStart = array();
	private $__returnValue;
	private $__enableReturnValueStubbing = false;
	private $__enableReturnValueFromCallback = false;
	private $__isDisableRealRunCall = false;

	public function __injectFunctionToRun($callback)
	{
		$this->__injectionsToRun[] = $callback;
	}

	public function __injectFunctionToRunStart($callback)
	{
		$this->__injectionsToRunStart[] = $callback;
	}

	public function __setRunReturnValue($value)
	{
		$this->__returnValue = $value;
		$this->__enableReturnValueStubbing = true;
	}

	public function __setRunReturnValueFromCallback($callback)
	{
		$this->__returnValue = $callback;
		$this->__enableReturnValueStubbing = true;
		$this->__enableReturnValueFromCallback = true;
	}

	public function __disableRealRunCall()
	{
		$this->__isDisableRealRunCall = true;
	}

	public function run()
	{
		$args = func_get_args();

		foreach ($this->__injectionsToRun as $callback)
			call_user_func_array($callback, $args);

		if (!$this->__isDisableRealRunCall)
			$returnValue = call_user_func_array('parent::' . __FUNCTION__, $args);
		else
			$returnValue = null;

		if ($this->__enableReturnValueStubbing)
		{
			if ($this->__enableReturnValueFromCallback)
				$returnValue = call_user_func($this->__returnValue);
			else
				$returnValue = $this->__returnValue;
		}

		return $returnValue;
	}

	protected function startRun()
	{
		$args = func_get_args();

		foreach ($this->__injectionsToRunStart as $callback)
			call_user_func_array($callback, $args);

		return call_user_func_array('parent::' . __FUNCTION__, $args);
	}
}