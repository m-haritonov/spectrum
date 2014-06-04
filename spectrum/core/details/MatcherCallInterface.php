<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\details;

interface MatcherCallInterface
{
	public function setTestedValue($testedValue);
	public function getTestedValue();
	
	public function setNot($not);
	public function getNot();
	
	public function setResult($result);
	public function getResult();
	
	public function setMatcherName($matcherName);
	public function getMatcherName();
	
	public function setMatcherArguments(array $matcherArguments);
	public function getMatcherArguments();
	
	public function setMatcherReturnValue($matcherReturnValue);
	public function getMatcherReturnValue();
	
	public function setMatcherException(\Exception $exception = null);
	public function getMatcherException();
	
	public function setFile($path);
	public function getFile();
	
	public function setLine($number);
	public function getLine();
}