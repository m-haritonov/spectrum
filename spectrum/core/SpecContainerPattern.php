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

namespace spectrum\core;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class SpecContainerPattern extends SpecContainer implements SpecContainerPatternInterface
{
	protected $arguments = array();

	public function setArguments($arguments)
	{
		$this->handleSpecModifyDeny();
		$this->arguments = $arguments;
	}

	public function getArguments()
	{
		return $this->arguments;
	}
}