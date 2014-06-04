<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\details;

class MatcherCall implements MatcherCallInterface
{
	protected $testedValue;
	protected $not;
	protected $result;
	protected $matcherName;
	protected $matcherArguments = array();
	protected $matcherReturnValue;
	protected $matcherException;
	protected $file;
	protected $line;

	public function setTestedValue($testedValue){ $this->testedValue = $testedValue; }
	public function getTestedValue(){ return $this->testedValue; }

	public function setNot($not){ $this->not = $not; }
	public function getNot(){ return $this->not; }

	public function setResult($result){ $this->result = $result; }
	public function getResult(){ return $this->result; }

	public function setMatcherName($matcherName){ $this->matcherName = $matcherName; }
	public function getMatcherName(){ return $this->matcherName; }

	public function setMatcherArguments(array $matcherArguments){ $this->matcherArguments = $matcherArguments; }
	public function getMatcherArguments(){ return $this->matcherArguments; }

	public function setMatcherReturnValue($matcherReturnValue){ $this->matcherReturnValue = $matcherReturnValue; }
	public function getMatcherReturnValue(){ return $this->matcherReturnValue; }
	
	public function setMatcherException(\Exception $exception = null){ $this->matcherException = $exception; }
	public function getMatcherException(){ return $this->matcherException; }
	
	public function setFile($path){ $this->file = $path; }
	public function getFile(){ return $this->file; }
	
	public function setLine($number){ $this->line = $number; }
	public function getLine(){ return $this->line; }
}