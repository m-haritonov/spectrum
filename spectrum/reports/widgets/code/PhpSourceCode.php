<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets\code;

class PhpSourceCode extends \spectrum\reports\widgets\Widget
{
	public function getStyles()
	{
		$expandedParentSelector = '.g-runResultsBuffer>.results>.result.expand';
		
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-code-phpSourceCode { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; max-height: 1.3em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); vertical-align: top; }' . $this->getNewline() .
				$this->getIndention() . $expandedParentSelector . ' .g-code-phpSourceCode { overflow: visible; max-width: none; max-height: none; white-space: pre; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml($sourceCode)
	{
		$sourceCode = trim($sourceCode);
		$sourceCode = $this->getLastLineIndention($sourceCode) . $sourceCode; 
		$highlightCodeFormatter = new \spectrum\library\HighlightCodeFormatter();
		$highlightCodeFormatter->setHighlightSpaces(false);
		$highlightCodeFormatter->setRemoveLeadingTabColumns(true);
		$sourceCode = $highlightCodeFormatter->format($sourceCode);
		return '<span class="g-code-phpSourceCode">' . $sourceCode . '</span>';
	}
	
	protected function getLastLineIndention($sourceCode)
	{
		$sourceCodeLength = mb_strlen($sourceCode);
		$pos = mb_strrpos($sourceCode, "\n") + 1;
		$indention = '';
		while ($pos < $sourceCodeLength)
		{
			$char = mb_substr($sourceCode, $pos, 1);
			
			if ($char != ' ' && $char != "\t")
				break;
			
			$indention .= $char;
			$pos++;
		}
		
		return $indention;
	}
}