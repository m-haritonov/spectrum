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

namespace spectrum\matchers\base;

/**
 * Matcher for greater than operator ($actual > $expected).
 * @return bool
 */
function gt($actual, $expected)
{
	return ($actual > $expected);
}