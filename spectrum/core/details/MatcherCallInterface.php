<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\details;

interface MatcherCallInterface {
	/**
	 * @param mixed $testedValue
	 */
	public function setTestedValue($testedValue);
	
	/**
	 * @return mixed
	 */
	public function getTestedValue();
	
	/**
	 * @param bool $not
	 */
	public function setNot($not);
	
	/**
	 * @return null|bool
	 */
	public function getNot();
	
	/**
	 * @param bool $result
	 */
	public function setResult($result);
	
	/**
	 * @return null|bool
	 */
	public function getResult();
	
	/**
	 * @param string $matcherName
	 */
	public function setMatcherName($matcherName);
	
	/**
	 * @return null|string
	 */
	public function getMatcherName();
	
	public function setMatcherArguments(array $matcherArguments);
	
	/**
	 * @return array
	 */
	public function getMatcherArguments();
	
	/**
	 * @param mixed $matcherReturnValue
	 */
	public function setMatcherReturnValue($matcherReturnValue);
	
	/**
	 * @return mixed
	 */
	public function getMatcherReturnValue();
	
	public function setMatcherException(\Exception $exception = null);
	
	/**
	 * @return null|\Exception
	 */
	public function getMatcherException();
	
	/**
	 * @param string $path
	 */
	public function setFile($path);
	
	/**
	 * @return null|string
	 */
	public function getFile();
	
	/**
	 * @param int $number
	 */
	public function setLine($number);
	
	/**
	 * @return null|int
	 */
	public function getLine();
}