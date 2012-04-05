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

namespace spectrum\reports\widgets\code\variables;

class UnknownVar extends Variable
{
	protected $type = 'unknown';

	protected function getHtmlForValue($variable)
	{
		return ' <span class="value">' . htmlspecialchars(print_r($variable, true)) . '</span>';
	}
}