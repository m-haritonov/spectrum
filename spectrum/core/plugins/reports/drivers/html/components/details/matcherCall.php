<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\details;

use spectrum\core\details\MatcherCallInterface;

class matcherCall extends \spectrum\core\plugins\reports\drivers\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-details-matcherCall { padding: 7px; }
			.app-details-matcherCall>.evaluatedValues { margin-bottom: 1em; }
			.app-details-matcherCall>.matcherException { margin-bottom: 1em; }
			.app-details-matcherCall>*>h1 { margin-bottom: 0.2em; font-size: 1em; }
			.app-details-matcherCall>.source>p .file .prefix:before { content: "\2026"; }
			.app-details-matcherCall>.source>p .file .prefix>span { display: none; }
			.app-resultBuffer>.results>.result.expanded .app-details-matcherCall>.source>p .file .prefix:before { display: none; }
			.app-resultBuffer>.results>.result.expanded .app-details-matcherCall>.source>p .file .prefix>span { display: inline; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @return string
	 */
	static public function getContent(MatcherCallInterface $details) {
		return
			'<div class="app-details-matcherCall">' . static::getHtmlEscapedOutputNewline() .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getContentForEvaluatedValues($details)) . static::getHtmlEscapedOutputNewline() .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getContentForMatcherException($details)) . static::getHtmlEscapedOutputNewline() .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getContentForSource($details)) . static::getHtmlEscapedOutputNewline() .
			'</div>';
	}

	/**
	 * @return string
	 */
	static protected function getContentForEvaluatedValues(MatcherCallInterface $details) {
		$content = '';
		$content .= '<div class="evaluatedValues">' . static::getHtmlEscapedOutputNewline();
		$content .= static::getHtmlEscapedOutputIndention() . '<h1>' . static::translateAndEscapeHtml('Evaluated values') . '</h1>' . static::getHtmlEscapedOutputNewline();
		$content .= static::getHtmlEscapedOutputIndention() . '<p>';
		$content .= static::callComponentMethod('code\method', 'getContent', array('be', array($details->getTestedValue())));

		if ($details->getNot()) {
			$content .= static::callComponentMethod('code\operator', 'getContent', array('->', 'us-ascii'));
			$content .= static::callComponentMethod('code\property', 'getContent', array('not', 'us-ascii'));
		}

		$content .= static::callComponentMethod('code\operator', 'getContent', array('->', 'us-ascii'));
		$content .= static::callComponentMethod('code\method', 'getContent', array($details->getMatcherName(), $details->getMatcherArguments()));
		$content .= '</p>' . static::getHtmlEscapedOutputNewline();
		$content .= '</div>';
		return $content;
	}

	/**
	 * @return null|string
	 */
	static protected function getContentForMatcherException(MatcherCallInterface $details) {
		if ($details->getMatcherException() === null) {
			return null;
		}
		
		return
			'<div class="matcherException">' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<h1 title="' . static::translateAndEscapeHtml('Exception thrown by "%matcherName%" matcher', array('%matcherName%' => static::convertToOutputCharset($details->getMatcherName()))) . '">' . 
					static::translateAndEscapeHtml('Matcher exception') . ':' . 
				'</h1>' . static::getHtmlEscapedOutputNewline() .
				
				static::getHtmlEscapedOutputIndention() . '<p>' . 
					static::callComponentMethod('code\variable', 'getContent', array($details->getMatcherException())) .
				'</p>' . static::getHtmlEscapedOutputNewline() .
			'</div>';
	}

	/**
	 * @return string
	 */
	static protected function getContentForSource(MatcherCallInterface $details) {
		$filename = $details->getFile();
		$filenameEndLength = 25;
		$filenameBegin = mb_substr($filename, 0, -$filenameEndLength, 'utf-8'); // Filenames are come in OS charset (conceivably in "utf-8")
		$filenameEnd = mb_substr($filename, -$filenameEndLength, mb_strlen($filename, 'utf-8'), 'utf-8'); // Filenames are come in OS charset (conceivably in "utf-8")
		
		return
			'<div class="source">' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<h1>' . static::translateAndEscapeHtml('Source') . '</h1>' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<p>' . 
					static::translateAndEscapeHtml('File') . ' ' .
					'"<span class="file">' . 
						($filenameBegin != '' ? '<span class="prefix"><span>' . static::escapeHtml(static::convertToOutputCharset($filenameBegin, 'utf-8')) . '</span></span>' : '') . 
						static::escapeHtml(static::convertToOutputCharset($filenameEnd, 'utf-8')) . 
					'</span>", ' .
					static::translateAndEscapeHtml('line') . ' ' .
					'<span class="line">' . static::escapeHtml($details->getLine()) . '</span>' .
				'</p>' . static::getHtmlEscapedOutputNewline() .
			'</div>';
	}
}