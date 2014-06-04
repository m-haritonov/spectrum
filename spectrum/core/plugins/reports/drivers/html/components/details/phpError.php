<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\details;

use \spectrum\core\details\PhpErrorInterface;

class phpError extends \spectrum\core\plugins\reports\drivers\html\components\component
{
	static public function getStyles()
	{
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-details-phpError { padding: 7px; }
			.c-details-phpError>.errorLevel { margin-bottom: 1em; }
			.c-details-phpError>.errorMessage { margin-bottom: 1em; }
			.c-details-phpError>*>h1 { margin-bottom: 0.2em; font-size: 1em; }
			.c-details-phpError>.source>p .file .prefix:before { content: "\2026"; }
			.c-details-phpError>.source>p .file .prefix>span { display: none; }
			.c-resultBuffer>.results>.result.expanded .c-details-phpError>.source>p .file .prefix:before { display: none; }
			.c-resultBuffer>.results>.result.expanded .c-details-phpError>.source>p .file .prefix>span { display: inline; }
		/*]]>*/</style>', 2);
	}

	static public function getHtml(PhpErrorInterface $details)
	{
		return
			'<div class="c-details-phpError">' . static::getHtmlEscapedOutputNewline() .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getHtmlForErrorLevel($details)) . static::getHtmlEscapedOutputNewline() .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getHtmlForErrorMessage($details)) . static::getHtmlEscapedOutputNewline() .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getHtmlForSource($details)) . static::getHtmlEscapedOutputNewline() .
			'</div>';
	}

	static protected function getHtmlForErrorLevel(PhpErrorInterface $details)
	{
		$errorLevel = $details->getErrorLevel();
		
		$output = '';
		$output .= '<div class="errorLevel">' . static::getHtmlEscapedOutputNewline();
		$output .= static::getHtmlEscapedOutputIndention() . '<h1>' . static::translateAndEscapeHtml('Error level') . '</h1>' . static::getHtmlEscapedOutputNewline();
		$output .= static::getHtmlEscapedOutputIndention() . '<p>' . static::escapeHtml($errorLevel) . ' (' . static::escapeHtml(static::getErrorLevelConstantNameByValue($errorLevel)) . ')</p>' . static::getHtmlEscapedOutputNewline();
		$output .= '</div>';
		return $output;
	}
	
	static protected function getErrorLevelConstantNameByValue($constantValue)
	{
		$constants = get_defined_constants(true);
		foreach ($constants['Core'] as $name => $value)
		{
			if ($value === $constantValue)
				return $name;
		}
		
		return null;
	}
	
	static protected function getHtmlForErrorMessage(PhpErrorInterface $details)
	{
		$output = '';
		$output .= '<div class="errorMessage">' . static::getHtmlEscapedOutputNewline();
		$output .= static::getHtmlEscapedOutputIndention() . '<h1>' . static::translateAndEscapeHtml('Error message') . '</h1>' . static::getHtmlEscapedOutputNewline();
		$output .= static::getHtmlEscapedOutputIndention() . '<p>' . static::escapeHtml($details->getErrorMessage()) . '</p>' . static::getHtmlEscapedOutputNewline();
		$output .= '</div>';
		return $output;
	}
	
	static protected function getHtmlForSource(PhpErrorInterface $details)
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