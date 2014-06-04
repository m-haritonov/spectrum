<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

use spectrum\Exception;

class ResultBuffer implements ResultBufferInterface
{
	/** @var \spectrum\core\SpecInterface */
	protected $ownerSpec;
	protected $results = array();
	protected $locked = false;

	public function __construct(\spectrum\core\SpecInterface $ownerSpec)
	{
		$this->ownerSpec = $ownerSpec;
	}

	public function getOwnerSpec()
	{
		return $this->ownerSpec;
	}

	/**
	 * @param bool|null $result true, false or null
	 * @param mixed $details Exception object, some string, backtrace info, etc.
	 */
	public function addResult($result, $details = null)
	{
		if ($this->locked)
			throw new Exception('ResultBuffer is locked');
		
		if ($result !== true && $result !== false && $result !== null)
			throw new Exception('ResultBuffer is accept only "true", "false" or "null"');
		
		$this->results[] = array(
			'result' => $result,
			'details' => $details,
		);
	}
	
	public function getResults()
	{
		return $this->results;
	}

	public function getTotalResult()
	{
		$hasNull = false;
		foreach ($this->results as $result)
		{
			if ($result['result'] === false)
				return false;
			else if ($result['result'] === null)
				$hasNull = true;
			else if ($result['result'] !== true)
				throw new Exception('ResultBuffer should be contain "true", "false" or "null" values only (now it is contain value of "' . gettype($result['result']) . '" type)');
		}

		if ($hasNull)
			return null;
		else if (count($this->results) > 0)
			return true;
		else
			return null;
	}
	
	public function lock()
	{
		$this->locked = true;
	}
	
	public function isLocked()
	{
		return $this->locked;
	}
}