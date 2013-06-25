<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets\resultBuffer\details;
use \spectrum\core\specs\asserts\CallDetailsInterface;

class MatcherCall extends Details
{
	public function getStyles()
	{
		$expandedParentSelector = '.g-resultBuffer>.results>.result.expand';

		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-resultBuffer-details-matcherCall>.callExpression { margin-bottom: 4px; }' . $this->getNewline() .
				$this->getIndention() . '.g-resultBuffer-details-matcherCall>.callExpression>.g-code-method>.methodName { font-weight: bold; }' . $this->getNewline() .
				$this->getIndention() . '.g-resultBuffer-details-matcherCall>div>.title { font-weight: bold; }' . $this->getNewline() .
				$this->getIndention() . '.g-resultBuffer-details-matcherCall>.returnValue { display: none; }' . $this->getNewline() .
				$this->getIndention() . $expandedParentSelector . ' .g-resultBuffer-details-matcherCall>.returnValue { display: block; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml(CallDetailsInterface $details)
	{
		$output = '';
		$output .= '<div class="g-resultBuffer-details-matcherCall g-resultBuffer-details">';
		$output .= $this->getHtmlForCallExpression($details);
		$output .= $this->getHtmlForThrownException($details);
		$output .= $this->getHtmlForReturnValue($details);
		$output .= '</div>';
		return $output;
	}

	protected function getHtmlForCallExpression(CallDetailsInterface $details)
	{
		$output = '';

		$output .= '<div class="callExpression">';
		$output .= $this->createWidget('code\Method')->getHtml('the', array($details->getTestedValue()));

		if ($details->getNot())
		{
			$output .= $this->createWidget('code\Operator')->getHtml('->');
			$output .= $this->createWidget('code\Property')->getHtml('not');
		}

		$output .= $this->createWidget('code\Operator')->getHtml('->');
		$output .= $this->createWidget('code\Method')->getHtml($details->getMatcherName(), $details->getMatcherArguments());
		$output .= '</div>';

		return $output;
	}

	protected function getHtmlForThrownException(CallDetailsInterface $details)
	{
		return
			'<div class="thrownException">
				<span class="title" title="' . $this->translate('Exception thrown by "%matcherName%" matcher', array('%matcherName%' => $details->getMatcherName())) . '">' .
					$this->translate('Matcher exception') . ':' .
				'</span> ' .

				$this->createWidget('code\Variable')->getHtml($details->getException()) .
			'</div>';
	}

	protected function getHtmlForReturnValue(CallDetailsInterface $details)
	{
		return
			'<div class="returnValue">
				<span class="title" title="' . $this->translate('Original value returned by "%matcherName%" matcher', array('%matcherName%' => $details->getMatcherName())) . '">' .
					$this->translate('Matcher return value') . ':' .
				'</span> ' .

				$this->createWidget('code\Variable')->getHtml($details->getMatcherReturnValue()) .
			'</div>';
	}
}