<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components;

use spectrum\core\SpecInterface;

class messages extends component {
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-messages { position: relative; margin: 0.5em 0 1em 0; }
			.c-messages>h1 { float: left; margin-bottom: 2px; padding: 0.3em 0.5em 0 0; color: #888; font-size: 0.9em; font-weight: normal; }
			.c-messages>ul { clear: both; float: left; list-style: none; }
			.c-messages>ul>li { margin-bottom: 2px; padding: 0.4em 7px; border-radius: 4px; background: #e5e5e5; }
			.c-messages>ul>li>.number { color: #888; font-size: 0.9em; }
		/*]]>*/</style>', 2);
	}

	static public function getContent(SpecInterface $spec) {
		$messages = $spec->messages->getAll();

		if (!count($messages)) {
			return null;
		}

		$content = '';
		$content .= '<div class="c-messages c-clearFix">' . static::getHtmlEscapedOutputNewline();
		$content .= static::getHtmlEscapedOutputIndention() . '<h1>' . static::translateAndEscapeHtml('Messages') . ':</h1>' . static::getHtmlEscapedOutputNewline();
		$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getContentForMessages($messages)) . static::getHtmlEscapedOutputNewline();
		$content .= '</div>';
		return $content;
	}
	
	static protected function getContentForMessages($messages) {
		$content = '';
		$content .= '<ul>' . static::getHtmlEscapedOutputNewline();
		
		$num = 0;
		foreach ($messages as $message) {
			$num++;
			$content .= static::getHtmlEscapedOutputIndention() . '<li>';
			$content .= '<span class="number">' . $num . '. </span>';
			$content .= static::escapeHtml(static::convertToOutputCharset($message));
			$content .= '</li>' . static::getHtmlEscapedOutputNewline();
		}

		$content .= '</ul>';
		return $content;
	}
}