<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\details;
use spectrum\config;
use \spectrum\core\details\MatcherCallInterface;

class matcherCall extends \spectrum\core\plugins\reports\drivers\html\components\component
{
	static public function getStyles()
	{
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-details-matcherCall { padding: 7px; }
			.c-details-matcherCall>.evaluatedValues { margin-bottom: 1em; }
			.c-details-matcherCall>.matcherException { margin-bottom: 1em; }
			.c-details-matcherCall>*>h1 { margin-bottom: 0.2em; font-size: 1em; }
			.c-details-matcherCall>.source>p .file .prefix:before { content: "\2026"; }
			.c-details-matcherCall>.source>p .file .prefix>span { display: none; }
			.c-resultBuffer>.results>.result.expanded .c-details-matcherCall>.source>p .file .prefix:before { display: none; }
			.c-resultBuffer>.results>.result.expanded .c-details-matcherCall>.source>p .file .prefix>span { display: inline; }
		/*]]>*/</style>', 2);
	}

	static public function getHtml(MatcherCallInterface $details)
	{
		return
			'<div class="c-details-matcherCall">' . static::getHtmlEscapedOutputNewline() .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getHtmlForEvaluatedValues($details)) . static::getHtmlEscapedOutputNewline() .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getHtmlForMatcherException($details)) . static::getHtmlEscapedOutputNewline() .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getHtmlForSource($details)) . static::getHtmlEscapedOutputNewline() .
			'</div>';
	}

	static protected function getHtmlForEvaluatedValues(MatcherCallInterface $details)
	{
		$output = '';
		$output .= '<div class="evaluatedValues">' . static::getHtmlEscapedOutputNewline();
		$output .= static::getHtmlEscapedOutputIndention() . '<h1>' . static::translateAndEscapeHtml('Evaluated values') . '</h1>' . static::getHtmlEscapedOutputNewline();
		$output .= static::getHtmlEscapedOutputIndention() . '<p>';
		$output .= static::callComponentMethod('code\method', 'getHtml', array('be', array($details->getTestedValue())));

		if ($details->getNot())
		{
			$output .= static::callComponentMethod('code\operator', 'getHtml', array('->', 'us-ascii'));
			$output .= static::callComponentMethod('code\property', 'getHtml', array('not', 'us-ascii'));
		}

		$output .= static::callComponentMethod('code\operator', 'getHtml', array('->', 'us-ascii'));
		$output .= static::callComponentMethod('code\method', 'getHtml', array($details->getMatcherName(), $details->getMatcherArguments()));
		$output .= '</p>' . static::getHtmlEscapedOutputNewline();
		$output .= '</div>';
		return $output;
	}
	
	static protected function getHtmlForMatcherException(MatcherCallInterface $details)
	{
		if ($details->getMatcherException() === null)
			return null;
		
		return
			'<div class="matcherException">' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<h1 title="' . static::translateAndEscapeHtml('Exception thrown by "%matcherName%" matcher', array('%matcherName%' => static::convertToOutputCharset($details->getMatcherName()))) . '">' . 
					static::translateAndEscapeHtml('Matcher exception') . ':' . 
				'</h1>' . static::getHtmlEscapedOutputNewline() .
				
				static::getHtmlEscapedOutputIndention() . '<p>' . 
					static::callComponentMethod('code\variable', 'getHtml', array($details->getMatcherException())) .
				'</p>' . static::getHtmlEscapedOutputNewline() .
			'</div>';
	}
	
	static protected function getHtmlForSource(MatcherCallInterface $details)
	{
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