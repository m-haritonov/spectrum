<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\code;

class method extends \spectrum\core\plugins\basePlugins\reports\drivers\html\components\component
{
	/**
	 * @param string $methodName String in "us-ascii" charset
	 * @param array $arguments Data in input charset
	 * @return string
	 */
	static public function getHtml($methodName, array $arguments, $inputCharset = null)
	{
		return
			'<span class="c-code-method">' .
				'<span class="methodName">' . static::escapeHtml(static::convertToOutputCharset($methodName, $inputCharset)) . '</span>' .
				static::callComponentMethod('code\operator', 'getHtml', array('(', 'us-ascii')) .
				'<span class="arguments">' . static::getHtmlForArguments($arguments, $inputCharset) . '</span>' .
				static::callComponentMethod('code\operator', 'getHtml', array(')', 'us-ascii')) .
			'</span>';
	}

	static public function getHtmlForArguments(array $arguments, $inputCharset)
	{
		$output = '';
		
		end($arguments);
		$lastKey = key($arguments);
		
		foreach ($arguments as $key => $argument)
		{
			$output .= static::callComponentMethod('code\variable', 'getHtml', array($argument, 0, $inputCharset));
			
			if ($key !== $lastKey)
				$output .= ', ';
		}

		return $output;
	}
}