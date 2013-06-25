<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs;

class ResultBuffer implements ResultBufferInterface
{
	/** @var \spectrum\core\specs\SpecInterface */
	protected $ownerSpec;
	protected $results = array();

	public function __construct(\spectrum\core\specs\SpecInterface $ownerSpec)
	{
		$this->ownerSpec = $ownerSpec;
	}

	public function getOwnerSpec()
	{
		return $this->ownerSpec;
	}

	/**
	 * @param mixed $details Exception object, some message, backtrace info, etc.
	 */
	public function addFailResult($details = null)
	{
		$this->results[] = array(
			'result' => false,
			'details' => $details,
		);
	}
	
	/**
	 * @param mixed $details Exception object, some message, backtrace info, etc.
	 */
	public function addSuccessResult($details = null)
	{
		$this->results[] = array(
			'result' => true,
			'details' => $details,
		);
	}
	
	public function getResults()
	{
		return $this->results;
	}

	public function getTotalResult()
	{
		foreach ($this->results as $result)
		{
			if (!$result['result'])
				return false;
		}

		if (count($this->results) > 0)
			return true;
		else
			return null;
	}
}