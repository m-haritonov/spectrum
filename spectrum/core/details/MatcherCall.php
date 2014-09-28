<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\details;

class MatcherCall implements MatcherCallInterface {
	/**
	 * @var mixed
	 */
	protected $testedValue;

	/**
	 * @var null|bool
	 */
	protected $not;

	/**
	 * @var null|bool
	 */
	protected $result;

	/**
	 * @var null|string
	 */
	protected $matcherName;

	/**
	 * @var array
	 */
	protected $matcherArguments = array();

	/**
	 * @var mixed
	 */
	protected $matcherReturnValue;

	/**
	 * @var null|\Exception
	 */
	protected $matcherException;

	/**
	 * @var null|string
	 */
	protected $file;

	/**
	 * @var null|int
	 */
	protected $line;

	/**
	 * @param mixed $testedValue
	 */
	public function setTestedValue($testedValue) { $this->testedValue = $testedValue; }

	/**
	 * @return mixed
	 */
	public function getTestedValue() { return $this->testedValue; }

	/**
	 * @param bool $not
	 */
	public function setNot($not) { $this->not = $not; }

	/**
	 * @return bool|null
	 */
	public function getNot() { return $this->not; }

	/**
	 * @param bool $result
	 */
	public function setResult($result) { $this->result = $result; }

	/**
	 * @return bool|null
	 */
	public function getResult() { return $this->result; }

	/**
	 * @param string $matcherName
	 */
	public function setMatcherName($matcherName) { $this->matcherName = $matcherName; }

	/**
	 * @return null|string
	 */
	public function getMatcherName() { return $this->matcherName; }

	public function setMatcherArguments(array $matcherArguments) { $this->matcherArguments = $matcherArguments; }

	/**
	 * @return array
	 */
	public function getMatcherArguments() { return $this->matcherArguments; }

	/**
	 * @param mixed $matcherReturnValue
	 */
	public function setMatcherReturnValue($matcherReturnValue) { $this->matcherReturnValue = $matcherReturnValue; }

	/**
	 * @return mixed
	 */
	public function getMatcherReturnValue() { return $this->matcherReturnValue; }
	
	public function setMatcherException(\Exception $exception = null) { $this->matcherException = $exception; }

	/**
	 * @return \Exception|null
	 */
	public function getMatcherException() { return $this->matcherException; }

	/**
	 * @param string $path
	 */
	public function setFile($path) { $this->file = $path; }

	/**
	 * @return null|string
	 */
	public function getFile() { return $this->file; }

	/**
	 * @param int $number
	 */
	public function setLine($number) { $this->line = $number; }

	/**
	 * @return int|null
	 */
	public function getLine() { return $this->line; }
}