<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specItemIt;
require_once dirname(__FILE__) . '/../../init.php';

abstract class Test extends \spectrum\core\SpecTest
{
	protected $currentSpecClass = '\spectrum\core\SpecItemIt';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecItemItMock';

/*** Test ware ***/

	protected function getErrorHandler()
	{
		$handler = set_error_handler(function(){});
		restore_error_handler();
		return $handler;
	}
}