<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core;

use spectrum\Exception;

/**
 * Be carefully, this exception not adds to ResultBuffer and should be throw only for softly break execution.
 */
class BreakException extends Exception
{
	
}