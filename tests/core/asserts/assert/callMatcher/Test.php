<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\asserts\assert\callMatcher;
use spectrum\core\asserts\Assert;

require_once dirname(__FILE__) . '/../../../../init.php';

class Test extends \spectrum\core\asserts\assert\Test
{

/*** Test ware ***/

	protected function createItWithMatchers()
	{
		$it = parent::createItWithMatchers();
		$it->errorHandling->setCatchExceptions(true);
		return $it;
	}
}