<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets\runResultsBuffer\details;
use spectrum\core\verifications\CallDetailsInterface;

class VerifyCall extends Details
{
	public function getStyles()
	{
		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer-details-verifyCall h1 { margin-bottom: 0.2em; font-size: 1em; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer-details-verifyCall .resultValues { margin-bottom: 1em; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml(CallDetailsInterface $details)
	{
		return
			'<div class="g-runResultsBuffer-details-verifyCall g-runResultsBuffer-details">' .
				'<div class="resultValues">' .
					'<h1>' . $this->translate('Evaluated values') . ':</h1>' .
					$this->getHtmlForResultValues($details) .
				'</div>' .
				'<div class="sourceCode">' .
					'<h1>' . $this->translate('Source code') . ':</h1>' .
					$this->getHtmlForSourceCode($details) .
				'</div>' .
			'</div>';
	}
	
	public function getHtmlForResultValues(CallDetailsInterface $details)
	{
		$html = '';
		$html .= htmlspecialchars($details->getVerifyFunctionName());
		$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('(');
		$html .= $this->getOwnerPlugin()->createWidget('code\Variable')->getHtml($details->getValue1());
		
		if ($details->getOperator() != null)
		{
			$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml(',') . ' ';
			$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('"');
			$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml($details->getOperator());
			$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('"');
			$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml(',') . ' ';
			$html .= $this->getOwnerPlugin()->createWidget('code\Variable')->getHtml($details->getValue2());
		}
		
		$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml(')');
		return $html;
	}
	
	public function getHtmlForSourceCode(CallDetailsInterface $details)
	{
		$html = '';
		$html .= htmlspecialchars($details->getVerifyFunctionName());
		$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('(');
		$html .= $this->getOwnerPlugin()->createWidget('code\PhpSourceCode')->getHtml($details->getValue1SourceCode());

		if ($details->getOperator() != null)
		{
			$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml(',') . ' ';
			$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('"');
			$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml($details->getOperator());
			$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('"');
			$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml(',') . ' ';
			$html .= $this->getOwnerPlugin()->createWidget('code\PhpSourceCode')->getHtml($details->getValue2SourceCode());
		}
		
		$html .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml(')');
		return $html;
	}
}