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
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class RunResultsBuffer implements RunResultsBufferInterface
{
	/** @var \spectrum\core\SpecInterface */
	protected $ownerSpec;
	protected $results = array();

	public function __construct(\spectrum\core\SpecInterface $ownerSpec)
	{
		$this->ownerSpec = $ownerSpec;
	}

	public function getOwnerSpec()
	{
		return $this->ownerSpec;
	}

	/**
	 * @param mixed $result Cast to boolean
	 * @param mixed $details Exception object, some message, backtrace info, etc.
	 */
	public function addResult($result, $details = null)
	{
		$this->results[] = array(
			'result' => (bool) $result,
			'details' => $details,
		);
	}

	public function getResults()
	{
		return $this->results;
	}

	public function calculateFinalResult()
	{
		foreach ($this->results as $result)
		{
			if (!$result['result'])
				return false;
		}

		if (count($this->results) > 0)
			return true;
		else
			return null; // Test empty (no asserts in test, no errors)
	}
}