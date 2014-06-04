<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code;

class operator extends \spectrum\core\plugins\reports\drivers\html\components\component
{
	static public function getStyles()
	{
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-code-operator { color: rgba(0, 0, 0, 0.6); }
		/*]]>*/</style>', 2);
	}

	/**
	 * @param string $operator String in "us-ascii" charset
	 * @return string
	 */
	static public function getHtml($operator, $inputCharset = null)
	{
		return '<span class="c-code-operator ' . static::escapeHtml(static::getOperatorName($operator)) . '">' . static::escapeHtml(static::convertToOutputCharset($operator, $inputCharset)) . '</span>';
	}

	static protected function getOperatorName($operator)
	{
		if ((string) $operator === '{' || (string) $operator === '}')
			return 'curlyBrace';
		else
			return null;
	}
}