<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components;

use spectrum\core\SpecInterface;

class totalInfo extends component
{
	static public function getStyles()
	{
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-totalInfo { margin: 1em 0; padding: 6px 10px; border-radius: 4px; background: #e5e5e5; }
			.c-totalInfo>div { display: inline; }
			.c-totalInfo h1 { display: inline; color: #333; font-size: 1em; }
		/*]]>*/</style>', 2);
	}

	static public function getHtml(SpecInterface $spec)
	{
		if ($spec->getParentSpecs())
			return null;

		return
			'<div class="c-totalInfo">' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<div class="result">' . static::getHtmlEscapedOutputNewline() .
					static::getHtmlEscapedOutputIndention(2) . '<h1>' . static::translateAndEscapeHtml('Total result') . ':</h1>' . static::getHtmlEscapedOutputNewline() .
					static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::callComponentMethod('totalResult', 'getHtml', array($spec)), 2) . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '</div> | ' . static::getHtmlEscapedOutputNewline() .

				static::getHtmlEscapedOutputIndention() . '<div class="details">' . static::getHtmlEscapedOutputNewline() .
					static::getHtmlEscapedOutputIndention(2) . static::translateAndEscapeHtml('Details') . ': ' . static::getHtmlEscapedOutputNewline() .
					static::getHtmlEscapedOutputIndention(2) . static::callComponentMethod('detailsControl', 'getHtml', array($spec)) . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '</div>' . static::getHtmlEscapedOutputNewline() .
			'</div>';
	}

	static public function getHtmlForUpdate(SpecInterface $spec)
	{
		if ($spec->getParentSpecs())
			return null;

		return static::callComponentMethod('totalResult', 'getHtmlForUpdate', array($spec));
	}
}