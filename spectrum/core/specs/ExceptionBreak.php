<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs;

/**
 * Be carefully, this exception not adds to ResultBuffer and should be throw only for softly break execution.
 */
class ExceptionBreak extends Exception
{
	
}