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

namespace spectrum\core\plugins\events\onRun;
use spectrum\core\SpecItemIt;
use spectrum\core\RunResultsBuffer;
use spectrum\core\World;

require_once dirname(__FILE__) . '/../../../../init.php';

class SpecContainerContextTest extends SpecContainerTest
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerContext';
}