<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

use spectrum\Exception;

/**
 * Be carefully, this exception not adds to Results and should be throw only for softly break execution.
 */
class BreakException extends Exception {}