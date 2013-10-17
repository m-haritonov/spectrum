<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\resultBuffer\details;
use \spectrum\core\MatcherCallDetailsInterface;

class MatcherCall extends Details
{
	public function getStyles()
	{
		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-resultBuffer-details-matcherCall>.evaluatedValues { margin-bottom: 1em; }' . $this->getNewline() .
				$this->getIndention() . '.g-resultBuffer-details-matcherCall>*>h1 { margin-bottom: 0.2em; font-size: 1em; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml(MatcherCallDetailsInterface $details)
	{
		return
			'<div class="g-resultBuffer-details-matcherCall g-resultBuffer-details">' .
				$this->getHtmlForEvaluatedValues($details) .
				$this->getHtmlForMatcherException($details) .
			'</div>';
	}

	protected function getHtmlForEvaluatedValues(MatcherCallDetailsInterface $details)
	{
		$output = '';

		$output .= '<div class="evaluatedValues">';
		$output .= '<h1>' . $this->translate('Evaluated values') . ':</h1>';
		
		$output .= '<div class="value">';
		$output .= $this->createWidget('code\Method')->getHtml('the', array($details->getTestedValue()));

		if ($details->getNot())
		{
			$output .= $this->createWidget('code\Operator')->getHtml('->');
			$output .= $this->createWidget('code\Property')->getHtml('not');
		}

		$output .= $this->createWidget('code\Operator')->getHtml('->');
		$output .= $this->createWidget('code\Method')->getHtml($details->getMatcherName(), $details->getMatcherArguments());
		$output .= '</div>';
		
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
				'<div class="value">' . 
					$this->createWidget('code\Variable')->getHtml($details->getMatcherException()) .
				'</div>' .
			'</div>';
	}
}