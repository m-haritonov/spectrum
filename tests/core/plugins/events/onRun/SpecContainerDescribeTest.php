<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\events\onRun;
use spectrum\core\SpecItemIt;
use spectrum\core\RunResultsBuffer;
use spectrum\core\World;

require_once dirname(__FILE__) . '/../../../../init.php';

class SpecContainerDescribeTest extends SpecContainerTest
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerDescribe';
}