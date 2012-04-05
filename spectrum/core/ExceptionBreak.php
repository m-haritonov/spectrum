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

namespace spectrum\core;

/**
 * Be carefully, this exception not adds to RunResultsBuffer and should be throw only for softly break execution.
 */
class ExceptionBreak extends Exception
{
	
}