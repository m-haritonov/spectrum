<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv\emptyStubs\verifications;

class Verification implements \spectrum\core\verifications\VerificationInterface
{
	public function __construct($value1, $operator = null, $value2 = null){}
	public function getCallDetails(){}
}