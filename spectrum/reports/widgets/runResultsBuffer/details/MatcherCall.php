<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace net\mkharitonov\spectrum\reports\widgets\runResultsBuffer\details;
use \net\mkharitonov\spectrum\core\asserts\MatcherCallDetailsInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class MatcherCall extends Details
{
	public function getStyles()
	{
		$expandedParentSelector = '.g-runResultsBuffer>.results>.result.expand';

		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer-details-matcherCall>.callExpression { margin-bottom: 4px; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer-details-matcherCall>.callExpression>.g-code-method>.methodName { font-weight: bold; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer-details-matcherCall>div>.title { font-weight: bold; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer-details-matcherCall>.returnValue { display: none; }' . $this->getNewline() .
				$this->getIndention() . $expandedParentSelector . ' .g-runResultsBuffer-details-matcherCall>.returnValue { display: block; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml(MatcherCallDetailsInterface $details)
	{
		$output = '';
		$output .= '<div class="g-runResultsBuffer-details-matcherCall g-runResultsBuffer-details">';
		$output .= $this->getHtmlForCallExpression($details);
		$output .= $this->getHtmlForThrownException($details);
		$output .= $this->getHtmlForReturnValue($details);
		$output .= '</div>';
		return $output;
	}

	protected function getHtmlForCallExpression(MatcherCallDetailsInterface $details)
	{
		$output = '';

		$output .= '<div class="callExpression">';
		$output .= $this->getOwnerPlugin()->createWidget('code\Method')->getHtml('be', array($details->getActualValue()));

		if ($details->getIsNot())
		{
			$output .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('->');
			$output .= $this->getOwnerPlugin()->createWidget('code\Property')->getHtml('not');
		}

		$output .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('->');
		$output .= $this->getOwnerPlugin()->createWidget('code\Method')->getHtml($details->getMatcherName(), $details->getMatcherArgs());
		$output .= '</div>';

		return $output;
	}

	protected function getHtmlForThrownException(MatcherCallDetailsInterface $details)
	{
		return
			'<div class="thrownException">
				<span class="title" title="' . $this->translate('Exception thrown by "%matcherName%" matcher callback', array('%matcherName%' => $details->getMatcherName())) . '">' .
					$this->translate('Matcher exception') . ':' .
				'</span> ' .

				$this->getOwnerPlugin()->createWidget('code\Variable')->getHtml($details->getException()) .
			'</div>';
	}

	protected function getHtmlForReturnValue(MatcherCallDetailsInterface $details)
	{
		return
			'<div class="returnValue">
				<span class="title" title="' . $this->translate('Original value returned by "%matcherName%" matcher callback', array('%matcherName%' => $details->getMatcherName())) . '">' .
					$this->translate('Matcher return value') . ':' .
				'</span> ' .

				$this->getOwnerPlugin()->createWidget('code\Variable')->getHtml($details->getMatcherReturnValue()) .
			'</div>';
	}
}