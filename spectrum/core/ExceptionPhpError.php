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

class ExceptionPhpError extends Exception
{
	protected $file;
	protected $line;
	protected $severity;

	public function __construct ($message = '', $code = 0, $severity = 0, $file = '', $line = '', \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
		
		$this->file = $file;
		$this->line = $line;
		$this->severity = $severity;
	}

	public function getSeverity()
	{
		return $this->severity;
	}
}