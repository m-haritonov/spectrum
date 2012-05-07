<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specItemIt\errorHandling\catchPhpErrors\enabled;
require_once __DIR__ . '/../../../../../init.php';

abstract class Test extends \spectrum\core\specItemIt\errorHandling\catchPhpErrors\Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->it->errorHandling->setCatchPhpErrors(true);
	}
}