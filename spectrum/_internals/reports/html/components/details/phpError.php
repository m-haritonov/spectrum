<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals\reports\html\components\details;

use spectrum\core\details\PhpErrorInterface;

class phpError extends \spectrum\_internals\reports\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-details-phpError { padding: 7px; }
			.app-details-phpError>.errorLevel { margin-bottom: 1em; }
			.app-details-phpError>.errorMessage { margin-bottom: 1em; }
			.app-details-phpError>*>h1 { margin-bottom: 0.2em; font-size: 1em; }
			.app-details-phpError>.source>p .file .prefix:before { content: "\2026"; }
			.app-details-phpError>.source>p .file .prefix>span { display: none; }
			.app-resultBuffer>.results>.result.expanded .app-details-phpError>.source>p .file .prefix:before { display: none; }
			.app-resultBuffer>.results>.result.expanded .app-details-phpError>.source>p .file .prefix>span { display: inline; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @return string
	 */
	static public function getContent(PhpErrorInterface $details) {
		return
			'<div class="app-details-phpError">' .
				static::getContentForErrorLevel($details) .
				static::getContentForErrorMessage($details) .
				static::getContentForSource($details) .
			'</div>';
	}

	/**
	 * @return string
	 */
	static protected function getContentForErrorLevel(PhpErrorInterface $details) {
		$errorLevel = $details->getErrorLevel();
		
		$content = '';
		$content .= '<div class="errorLevel">';
		$content .= '<h1>' . static::translateAndEscapeHtml('Error level') . '</h1>';
		$content .= '<p>' . static::escapeHtml($errorLevel) . ' (' . static::escapeHtml(static::getErrorLevelConstantNameByValue($errorLevel)) . ')</p>';
		$content .= '</div>';
		return $content;
	}

	/**
	 * @param int $constantValue
	 * @return null|string
	 */
	static protected function getErrorLevelConstantNameByValue($constantValue) {
		$constants = get_defined_constants(true);
		foreach ($constants['Core'] as $name => $value) {
			if ($value === $constantValue) {
				return $name;
			}
		}
		
		return null;
	}

	/**
	 * @return string
	 */
	static protected function getContentForErrorMessage(PhpErrorInterface $details) {
		$content = '';
		$content .= '<div class="errorMessage">';
		$content .= '<h1>' . static::translateAndEscapeHtml('Error message') . '</h1>';
		$content .= '<p>' . static::escapeHtml($details->getErrorMessage()) . '</p>';
		$content .= '</div>';
		return $content;
	}

	/**
	 * @return string
	 */
	static protected function getContentForSource(PhpErrorInterface $details) {
		$filename = $details->getFile();
		$filenameEndLength = 25;
		$filenameBegin = mb_substr($filename, 0, -$filenameEndLength, 'utf-8'); // Filenames are come in OS charset (conceivably in "utf-8")
		$filenameEnd = mb_substr($filename, -$filenameEndLength, mb_strlen($filename, 'utf-8'), 'utf-8'); // Filenames are come in OS charset (conceivably in "utf-8")
		
		return
			'<div class="source">' .
				'<h1>' . static::translateAndEscapeHtml('Source') . '</h1>' .
				'<p>' . 
					static::translateAndEscapeHtml('File') . ' ' .
					'"<span class="file">' . 
						($filenameBegin != '' ? '<span class="prefix"><span>' . static::escapeHtml(static::convertToOutputCharset($filenameBegin, 'utf-8')) . '</span></span>' : '') . 
						static::escapeHtml(static::convertToOutputCharset($filenameEnd, 'utf-8')) . 
					'</span>", ' .
					static::translateAndEscapeHtml('line') . ' ' .
					'<span class="line">' . static::escapeHtml($details->getLine()) . '</span>' .
				'</p>' .
			'</div>';
	}
}