<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
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