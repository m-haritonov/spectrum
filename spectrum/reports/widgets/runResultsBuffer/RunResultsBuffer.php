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

namespace net\mkharitonov\spectrum\reports\widgets\runResultsBuffer;
use \net\mkharitonov\spectrum\core\asserts\MatcherCallDetailsInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class RunResultsBuffer extends \net\mkharitonov\spectrum\reports\widgets\Widget
{
	/**
	 * @var \net\mkharitonov\spectrum\reports\widgets\code\Code
	 */
	protected $codeWidget;

	public function __construct(\net\mkharitonov\spectrum\reports\Plugin $ownerPlugin)
	{
		parent::__construct($ownerPlugin);
		$this->codeWidget = $this->getOwnerPlugin()->createWidget('code');
	}

	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer { position: relative; margin: 0.5em 0 1em 0; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>h1 { float: left; margin-bottom: 2px; padding: 0.3em 0.5em 0 0.5em; color: #888; font-size: 0.9em; font-weight: normal; }' . $this->getNewline() .

				$this->getIndention() . '.g-runResultsBuffer>.results { clear: both; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result { float: left; position: relative; margin: 0 2px 2px 0; border: 1px solid; border-left: 0; border-top: 0; border-radius: 4px; white-space: nowrap; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result>a.expand { float: left; min-width: 19px; margin-right: 2px; padding: 2px 0; border-radius: 4px 0 4px 0; font-size: 0.9em; font-weight: bold; text-decoration: none; text-align: center; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result>.num { float: left; margin-right: 2px; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result>.value { float: left; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result>.g-runResultsBuffer-details { clear: both; }' . $this->getNewline() .

				$this->getIndention() . '.g-runResultsBuffer>.results>.result.true { border-color: #b5dfb5; background: #ccffcc; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.true>.num { background: #b5dfb5; color: #3a473a; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.true>.value { background: #b5dfb5; color: #3a473a; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.true>a.expand { background: #85cc8c; color: #e4ffe0; }' . $this->getNewline() .

				$this->getIndention() . '.g-runResultsBuffer>.results>.result.false { border-color: #e2b5b5; background: #ffcccc; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.false>.num { background: #e2b5b5; color: #3d3232; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.false>.value { background: #e2b5b5; color: #3d3232; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.false>a.expand { background: #db9a9a; color: #ffe3db; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getScripts()
	{
		return
			'<script type="text/javascript">
				document.addEventListener("DOMContentLoaded", function()
				{
					var resultNodes = document.body.querySelectorAll(".g-runResultsBuffer>.results>.result");

					for (var key in resultNodes)
					{
						// "dblclick" event not used for select text by double click (and further copy to clipboard) feature support
						resultNodes[key].addEventListener("click", function(e){
							e.preventDefault();
							if (e.button == 1)
								toggleExpand(e.currentTarget.querySelector("a.expand"));
						});

						resultNodes[key].querySelector("a.expand").addEventListener("click", function(e){
							e.preventDefault();
							toggleExpand(e.currentTarget);
						});
					}

					function toggleExpand(expandLinkNode)
					{
						var resultNode = expandLinkNode.parentNode;

						if (hasClass(resultNode, "expand"))
						{
							expandLinkNode.innerHTML = "+";
							removeClass(resultNode, "expand");
						}
						else
						{
							expandLinkNode.innerHTML = "-";
							addClass(resultNode, "expand");
						}
					}

					function hasClass(node, className)
					{
						return (node.className.match(new RegExp("(\\\\s|^)" + className + "(\\\\s|$)")) !== null);
					}

					function addClass(node, className)
					{
						if (!hasClass(node, className))
							node.className += " " + className;
					}

					function removeClass(node, className)
					{
						if (hasClass(node, className))
							node.className = node.className.replace(new RegExp("(\\\\s|^)" + className + "(\\\\s|$)"), " ");
					}
				});' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtml()
	{
		if (!($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecItemInterface))
			return null;

		$output = '';

		$output .= '<div class="g-runResultsBuffer g-clearfix">' . $this->getNewline();
		$output .= $this->getIndention() . '<h1>' . $this->translate('Run results buffer contains') . ':</h1>' . $this->getNewline();
		$output .= $this->getIndention() . '<div class="results">' . $this->getNewline();
		$num = 0;
		foreach ($this->getOwnerPlugin()->getOwnerSpec()->getRunResultsBuffer()->getResults() as $result)
		{
			$num++;
			$output .= $this->getIndention(2) . '<div class="result ' . ($result['result'] ? 'true' : 'false') . '">' . $this->getNewline();
			$output .= $this->getIndention(3) . '<a href="#" class="expand" title="' . $this->translate('Show full details (also available by mouse middle click on the card)') . '">+</a>' . $this->getNewline();
			$output .= $this->getIndention(3) . '<div class="num" title="' . $this->translate('Order in run results buffer') . '">' . $this->translate('No.') . ' ' . $num . '</div>' . $this->getNewline();
			$output .= $this->getIndention(3) . '<div class="value" title="' . $this->translate('Result') . '">' . ($result['result'] ? 'true' : 'false') . '</div>' . $this->getNewline();
			$output .= $this->getHtmlForResultDetails($result['details']) . $this->getNewline();
			$output .= $this->getIndention(2) . '</div>' . $this->getNewline();
		}

		$output .= $this->getIndention() . '</div>' . $this->getNewline();

		$output .= '</div>' . $this->getNewline();

		return $output;
	}

	protected function getHtmlForResultDetails($details)
	{
		if (is_object($details) && $details instanceof MatcherCallDetailsInterface)
			$widget = $this->getOwnerPlugin()->createWidget('matcherCallDetails');
		else
			$widget = $this->getOwnerPlugin()->createWidget('unknownDetails');

		return $widget->getHtml($details);
	}
}