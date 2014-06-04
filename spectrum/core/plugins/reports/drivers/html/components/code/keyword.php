<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code;

class keyword extends \spectrum\core\plugins\reports\drivers\html\components\component
{
	static public function getStyles()
	{
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-code-keyword { color: rgba(0, 0, 0, 0.6); }
		/*]]>*/</style>', 2);
	}

	/**
	 * @param string $keyword String in "us-ascii" charset
	 * @return string
	 */
	static public function getHtml($keyword, $inputCharset = null)
	{
		return '<span class="c-code-keyword">' . static::escapeHtml(static::convertToOutputCharset($keyword, $inputCharset)) . '</span>';
	}
}