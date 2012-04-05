<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\asserts\assert\callMatcher\matcherReturnTrue;
use spectrum\core\asserts\Assert;

require_once dirname(__FILE__) . '/../../../../../init.php';

class BreakOnFirstMatcherFailDisabledTest extends Test
{

/*** Test ware ***/

	protected function createItWithMatchers()
	{
		$it = parent::createItWithMatchers();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		return $it;
	}
}