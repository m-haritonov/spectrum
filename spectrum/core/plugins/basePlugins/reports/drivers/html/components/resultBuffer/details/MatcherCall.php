<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\resultBuffer\details;
use \spectrum\core\MatcherCallDetailsInterface;

class MatcherCall extends Details
{
	public function getStyles()
	{
		$expandedParentSelector = '.c-resultBuffer>.results>.result.expand ';
		
		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer-details-matcherCall>.evaluatedValues { margin-bottom: 1em; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer-details-matcherCall>.matcherException { margin-bottom: 1em; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer-details-matcherCall>*>h1 { margin-bottom: 0.2em; font-size: 1em; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer-details-matcherCall>.source>p .file .prefix:before { content: "\2026"; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer-details-matcherCall>.source>p .file .prefix>span { display: none; }' . $this->getNewline() .
				$this->getIndention() . $expandedParentSelector . '.c-resultBuffer-details-matcherCall>.source>p .file .prefix:before { display: none; }' . $this->getNewline() .
				$this->getIndention() . $expandedParentSelector . '.c-resultBuffer-details-matcherCall>.source>p .file .prefix>span { display: inline; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml(MatcherCallDetailsInterface $details)
	{
		return
			'<div class="c-resultBuffer-details-matcherCall c-resultBuffer-details">' .
				$this->getHtmlForEvaluatedValues($details) .
				$this->getHtmlForMatcherException($details) .
				$this->getHtmlForSource($details) .
			'</div>';
	}

	protected function getHtmlForEvaluatedValues(MatcherCallDetailsInterface $details)
	{
		$output = '';

		$output .= '<div class="evaluatedValues">';
		$output .= '<h1>' . $this->translate('Evaluated values') . ':</h1>';
		
		$output .= '<p>';
		$output .= $this->createComponent('code\Method')->getHtml('the', array($details->getTestedValue()));

		if ($details->getNot())
		{
			$output .= $this->createComponent('code\Operator')->getHtml('->');
			$output .= $this->createComponent('code\Property')->getHtml('not');
		}

		$output .= $this->createComponent('code\Operator')->getHtml('->');
		$output .= $this->createComponent('code\Method')->getHtml($details->getMatcherName(), $details->getMatcherArguments());
		$output .= '</p>';
		
		$output .= '</div>';

		return $output;
	}
	
	protected function getHtmlForMatcherException(MatcherCallDetailsInterface $details)
	{
		if ($details->getMatcherException() === null)
			return null;
		
		return
			'<div class="matcherException">' .
				'<h1 title="' . $this->translate('Exception thrown by "%matcherName%" matcher', array('%matcherName%' => $details->getMatcherName())) . '">' . 
					$this->translate('Matcher exception') . ':' . 
				'</h1>' .
				'<p>' . 
					$this->createComponent('code\Variable')->getHtml($details->getMatcherException()) .
				'</p>' .
			'</div>';
	}
	
	protected function getHtmlForSource(MatcherCallDetailsInterface $details)
	{
		$filename = $details->getFile();
		$filenameEndLength = 25;
		$filenameBegin = mb_substr($filename, 0, -$filenameEndLength);
		
		return
			'<div class="source">' .
				'<h1>' . $this->translate('Source') . ':</h1>' .
				'<p>' . 
					'File ' .
					'"<span class="file">' . 
						($filenameBegin != '' ? '<span class="prefix"><span>' . $filenameBegin . '</span></span>' : '') . 
						mb_substr($filename, -$filenameEndLength) . 
					'</span>"' .
					', line <span class="line">' . $details->getLine() . '</span>' .
				'</p>' .
			'</div>';
	}
}