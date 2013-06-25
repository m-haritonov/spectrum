<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\library;

/*class CodeParser implements CodeParserInterface
{
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
}*/