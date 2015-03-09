<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\html\components\code;

class operator extends \spectrum\core\_private\reports\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-code-operator { color: rgba(0, 0, 0, 0.6); }
		/*]]>*/</style>', 2);
	}

	/**
	 * @param string $operator String in "US-ASCII" charset
	 * @return string
	 */
	static public function getContent($operator, $inputCharset = null) {
		return '<span class="app-code-operator ' . static::escapeHtml(static::getOperatorName($operator)) . '">' . static::escapeHtml(static::convertToOutputCharset($operator, $inputCharset)) . '</span>';
	}

	/**
	 * @param string $operator
	 * @return null|string
	 */
	static protected function getOperatorName($operator) {
		if ((string) $operator === '{' || (string) $operator === '}') {
			return 'curlyBrace';
		} else {
			return null;
		}
	}
}