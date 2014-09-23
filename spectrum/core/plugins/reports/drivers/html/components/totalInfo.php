<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components;

use spectrum\core\SpecInterface;

class totalInfo extends component {
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-totalInfo { margin: 1em 0; padding: 6px 10px; border-radius: 4px; background: #e5e5e5; }
			.app-totalInfo>div { display: inline; }
			.app-totalInfo h1 { display: inline; color: #333; font-size: 1em; }
		/*]]>*/</style>', 2);
	}

	static public function getContent(SpecInterface $spec) {
		if ($spec->getParentSpecs()) {
			return null;
		}

		return
			'<div class="app-totalInfo">' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<div class="result">' . static::getHtmlEscapedOutputNewline() .
					static::getHtmlEscapedOutputIndention(2) . '<h1>' . static::translateAndEscapeHtml('Total result') . ':</h1>' . static::getHtmlEscapedOutputNewline() .
					static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::callComponentMethod('totalResult', 'getContent', array($spec)), 2) . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '</div> | ' . static::getHtmlEscapedOutputNewline() .

				static::getHtmlEscapedOutputIndention() . '<div class="details">' . static::getHtmlEscapedOutputNewline() .
					static::getHtmlEscapedOutputIndention(2) . static::translateAndEscapeHtml('Details') . ': ' . static::getHtmlEscapedOutputNewline() .
					static::getHtmlEscapedOutputIndention(2) . static::callComponentMethod('detailsControl', 'getContent', array($spec)) . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '</div>' . static::getHtmlEscapedOutputNewline() .
			'</div>';
	}

	static public function getContentForUpdate(SpecInterface $spec) {
		if ($spec->getParentSpecs()) {
			return null;
		}

		return static::callComponentMethod('totalResult', 'getContentForUpdate', array($spec));
	}
}