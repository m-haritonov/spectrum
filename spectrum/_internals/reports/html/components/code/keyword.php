<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals\reports\html\components\code;

class keyword extends \spectrum\_internals\reports\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-code-keyword { color: rgba(0, 0, 0, 0.6); }
		/*]]>*/</style>', 2);
	}

	/**
	 * @param string $keyword String in "US-ASCII" charset
	 * @return string
	 */
	static public function getContent($keyword, $inputCharset = null) {
		return '<span class="app-code-keyword">' . static::escapeHtml(static::convertToOutputCharset($keyword, $inputCharset)) . '</span>';
	}
}