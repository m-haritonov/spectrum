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

namespace spectrum\core\asserts\assert\callMatcher\matcherReturnTrue;
use spectrum\core\asserts\Assert;

require_once dirname(__FILE__) . '/../../../../../init.php';

class BreakOnFirstMatcherFailEnabledTest extends Test
{

/*** Test ware ***/

	protected function createItWithMatchers()
	{
		$it = parent::createItWithMatchers();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		return $it;
	}
}