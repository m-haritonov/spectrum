<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\verifications;

class Verification implements VerificationInterface
{
	protected $callDetails;
	protected $acceptableOperators = array(
		'==',
		'===',
		'!=',
		'<>',
		'!==',
		'<',
		'>',
		'<=',
		'>=',
		'instanceof',
		'!instanceof',
		'throws',
		'!throws',
	);

	public function __construct($value1, $operator = null, $value2 = null)
	{
		$specItem = $this->getRunningSpecItem();
		
		try
		{
			if (func_num_args() != 1 && func_num_args() != 3)
				throw new Exception('Verification can accept only 1 or 3 arguments (now ' . func_num_args() . ' arguments passed)');
			
			if ($operator !== null)
				$operator = trim($operator);
			
			if ($operator != null && !in_array($operator, $this->acceptableOperators))
				throw new Exception('Operator "' . $operator . '" forbidden in verification (acceptable operators: "' . implode(', ', $this->acceptableOperators) . '")');
	
			$result = $this->evaluateResult($value1, $operator, $value2);
			$verifyFunctionName = $this->getCurrentVerifyFunctionName();
			$argumentsSourceCode = $this->parseArgumentsSourceCode($this->getCurrentVerifyCallSourceCode($verifyFunctionName), $verifyFunctionName);
			
			$this->callDetails = $this->createVerifyCallDetails();
			$this->callDetails->setVerifyFunctionName($verifyFunctionName);
			$this->callDetails->setValue1($value1);
			$this->callDetails->setValue1SourceCode(trim(@$argumentsSourceCode[0]));
			$this->callDetails->setOperator($operator);
			$this->callDetails->setValue2($value2);
			$this->callDetails->setValue2SourceCode(trim(@$argumentsSourceCode[2]));
			$this->callDetails->setResult($result);
			$details = $this->callDetails;
		}
		catch (\Exception $e)
		{
			if ($specItem->errorHandling->getCatchExceptionsCascade())
			{
				$result = false;
				$details = $e;
			}
			else
				throw $e;
		}
		
		$specItem->getRunResultsBuffer()->addResult($result, $details);
		
		if (!$result && $specItem->errorHandling->getBreakOnFirstMatcherFailCascade())
			throw new \spectrum\core\ExceptionBreak();
	}
	
	public function getCallDetails()
	{
		return $this->callDetails;
	}
	
	protected function evaluateResult(&$value1, $operator, &$value2)
	{
		if ($operator == null)
			return ($value1 == true);
		else
		{
			if ($operator == 'instanceof')
			{
				if (!is_object($value1))
					throw new Exception('"instanceof" operator in verification can accept only object as first operand (now ' . gettype($value1) . ' passed)');
				
				return ($value1 instanceof $value2);
			}
			else if ($operator == '!instanceof')
			{
				if (!is_object($value1))
					throw new Exception('"!instanceof" operator in verification can accept only object as first operand (now ' . gettype($value1) . ' passed)');
				
				return !($value1 instanceof $value2);
			}
			else if ($operator == 'throws')
				return $this->evaluateThrowsOperator($value1, $operator, $value2);
			else if ($operator == '!throws')
				return !$this->evaluateThrowsOperator($value1, $operator, $value2);
			else
				return eval('return ($value1 ' . $operator . ' $value2);');
		}
	}
	
	/**
	 * Return true, if code in $value1 function throws exception instance of $value2 class.
	 * @param string|array $value2 Accept string with class name or array with class, string in message, code. Examples: 
	 *                             '\SomeException' 
	 *                             array('\SomeException')
	 *                             array('\SomeException', 'string in exception message') 
	 *                             array('\SomeException', 'string in exception message', code) 
	 *                             array('\SomeException', null, code)
	 *                             array(null, null, code)
	 *                             array(null, 'string in exception message', null)
	 * @return bool
	 */
	protected function evaluateThrowsOperator(&$value1, $operator, &$value2)
	{
		if (!is_callable($value1))
			throw new Exception('"' . $operator . '" operator in verification can accept only callable function as first operand (now passed callback not callable)');
		
		if (is_array($value2))
		{
			if (count($value2) == 0)
				throw new Exception('"' . $operator . '" operator in verification can\'t accept empty array as second operand');
			
			if (@$value2[0] === null && @$value2[1] === null && @$value2[2] === null)
				throw new Exception('"' . $operator . '" operator in verification can\'t accept array with all null elements as second operand');
			
			$expectedClass = @$value2[0];
			$expectedStringInMessage = @$value2[1];
			$expectedCode = @$value2[2];
		}
		else if (is_string($value2))
		{
			$expectedClass = @$value2;
			$expectedStringInMessage = null;
			$expectedCode = null;
		}
		else
			throw new Exception('"' . $operator . '" operator in verification can accept only string or array as second operand (now passed ' . gettype($value2) . ')');
		
		if ($expectedClass == null)
			$expectedClass = '\Exception';
	
		if (!is_subclass_of($expectedClass, '\Exception') && $expectedClass != '\Exception')
			throw new Exception('Excepted class in "' . $operator . '" verification should be subclass of "\Exception" (now class "' . $expectedClass . '" not subclass of "\Exception")');
	
		try
		{
			call_user_func($value1);
		}
		catch (\Exception $e)
		{
			$value1 = $e;
			$actualClass = '\\' . get_class($e);
	
			if ($actualClass == $expectedClass || is_subclass_of($actualClass, $expectedClass))
			{
				$actualMessage = $e->getMessage();
				$actualCode = $e->getCode();
	
				if ($expectedStringInMessage !== null && mb_stripos($actualMessage, $expectedStringInMessage) === false)
					return false;
	
				if ($expectedCode !== null && $actualCode != $expectedCode)
					return false;
	
				return true;
			}
		}

		return false;
	}

	/**
	 * @return \spectrum\core\SpecItemIt
	 */
	protected function getRunningSpecItem()
	{
		$registryClass = \spectrum\core\Config::getRegistryClass();
		return $registryClass::getRunningSpecItem();
	}

	/**
	 * @return \spectrum\core\verifications\CallDetails
	 */
	protected function createVerifyCallDetails()
	{
		$verifyCallDetailsClass = \spectrum\core\Config::getVerificationCallDetailsClass();
		return new $verifyCallDetailsClass();
	}
	
	protected function getCurrentVerifyCallSourceCode($verifyFunctionName)
	{
		$trace = null;
		foreach (debug_backtrace() as $trace)
		{
			if (@$trace['function'] == $verifyFunctionName && @$trace['class'] == '')
				break;
		}
		
		$sourceCode = '';
		
		$fp = @fopen(@$trace['file'], 'r');
		if ($fp)
		{
			$num = 0;
			while (true)
			{
				$num++;
				$line = fgets($fp);
				if ($line === false)
					break;
				
				foreach (@token_get_all('<?php ' . $line . ' ?>') as $token)
				{
					if (is_array($token) && $token[0] == T_STRING && $token[1] == $verifyFunctionName)
						$sourceCode = '';
				}
				
				$sourceCode .= $line;
				
				if ($num == @$trace['line'])
					break;
			}
			
			fclose($fp);
		}
		
		return $sourceCode;
	}
	
	protected function parseArgumentsSourceCode($functionCallSourceCode, $verifyFunctionName)
	{
		$result = array();
		$currentCollectionArgumentIndex = null;
		$verifyFunctionOpened = false;
		$openRoundBracketsCount = 0;
		$closeRoundBracketsCount = 0;
		$openCurlyBracketsCount = 0;
		$closeCurlyBracketsCount = 0;
		foreach (@token_get_all('<?php ' . $functionCallSourceCode . ' ?>') as $token)
		{
			if (!$verifyFunctionOpened && is_array($token) && $token[0] == T_STRING && $token[1] == $verifyFunctionName)
				$verifyFunctionOpened = true;
			else if ($verifyFunctionOpened && !is_array($token) && $token == '(')
				$openRoundBracketsCount++;
			else if ($verifyFunctionOpened && !is_array($token) && $token == ')')
				$closeRoundBracketsCount++;
			else if ($verifyFunctionOpened && !is_array($token) && $token == '{')
				$openCurlyBracketsCount++;
			else if ($verifyFunctionOpened && !is_array($token) && $token == '}')
				$closeCurlyBracketsCount++;
			
			if ($verifyFunctionOpened && $openRoundBracketsCount > 0 && $openRoundBracketsCount == $closeRoundBracketsCount && $openCurlyBracketsCount == $closeCurlyBracketsCount)
				break;
			
			if (!is_array($token) && $token == '(' && $openRoundBracketsCount == 1 && $closeRoundBracketsCount == 0)
			{
				$currentCollectionArgumentIndex = 0;
				continue;
			}
			else if (!is_array($token) && $token == ',' && $openRoundBracketsCount == $closeRoundBracketsCount + 1 && $openCurlyBracketsCount == $closeCurlyBracketsCount)
			{
				$currentCollectionArgumentIndex++;
				continue;
			}
			
			if ($currentCollectionArgumentIndex !== null)
			{
				if (is_array($token))
					@$result[$currentCollectionArgumentIndex] .= $token[1];
				else
					@$result[$currentCollectionArgumentIndex] .= $token;
			}
		}
		
		return $result;
	}
	
	protected function getCurrentVerifyFunctionName()
	{
		return 'verify';
	}
}