<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets;

class TotalInfo extends \spectrum\reports\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-totalInfo { margin: 1em 0; padding: 6px 10px; border-radius: 4px; background: #ddd; }' . $this->getNewline() .
				$this->getIndention() . '.g-totalInfo>div { display: inline; }' . $this->getNewline() .
				$this->getIndention() . '.g-totalInfo h1 { display: inline; color: #333; font-size: 1em; }' . $this->getNewline() .
				$this->getIndention() . '.g-totalInfo a { border-bottom-width: 1px; border-bottom-style: dotted; text-decoration: none; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getScripts()
	{
		return
			'<script type="text/javascript">
				document.addEventListener("DOMContentLoaded", function()
				{
					var totalInfoNodes = document.querySelectorAll(".g-totalInfo");
					for (var i = 0; i < totalInfoNodes.length; i++)
					{
						var totalInfoNode = totalInfoNodes[i];

						totalInfoNode.querySelector(".specChildren .expandAll").addEventListener("click", function(e){
							e.preventDefault();
							tools.addClass(".g-specList>li.container", "expand");
						});

						totalInfoNode.querySelector(".specChildren .collapseAll").addEventListener("click", function(e){
							e.preventDefault();
							tools.removeClass(".g-specList>li.container", "expand");
						});

						/**/

						totalInfoNode.querySelector(".specDetails .expandAll").addEventListener("click", function(e){
							e.preventDefault();
							tools.addClass(".g-specList>li.item", "expand");
						});

						totalInfoNode.querySelector(".specDetails .collapseAll").addEventListener("click", function(e){
							e.preventDefault();
							tools.removeClass(".g-specList>li.item", "expand");
						});

						/**/

						totalInfoNode.querySelector(".resultDetails .expandAll").addEventListener("click", function(e){
							e.preventDefault();
							tools.addClass(".g-runResultsBuffer>.results>.result", "expand");
						});

						totalInfoNode.querySelector(".resultDetails .collapseAll").addEventListener("click", function(e){
							e.preventDefault();
							tools.removeClass(".g-runResultsBuffer>.results>.result", "expand");
						});
					}
				});' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtml()
	{
		if ($this->getOwnerPlugin()->getOwnerSpec()->getParent())
			return;


		return
			'<div class="g-totalInfo">' . $this->getNewline() .
				'<div class="result">' . $this->getNewline() .
					'<h1>' . $this->translate('Total result') . ':</h1>' . $this->getNewline() .
					$this->prependIndentionToEachLine($this->getOwnerPlugin()->createWidget('finalResult\Result')->getHtml()) . $this->getNewline() .
				'</div> | ' . $this->getNewline() .

				'<div class="specChildren">' .
					'Spec children: ' .
					'<a href="#" class="expandAll">' . $this->translate('expand all') . '</a>, ' .
					'<a href="#" class="collapseAll">' . $this->translate('collapse all') . '</a>' .
				'</div> | ' . $this->getNewline() .

				'<div class="specDetails">' .
					'Spec details: ' .
					'<a href="#" class="expandAll">' . $this->translate('expand all') . '</a>, ' .
					'<a href="#" class="collapseAll">' . $this->translate('collapse all') . '</a>' .
				'</div> | ' . $this->getNewline() .

				'<div class="resultDetails">' .
					'Result details: ' .
					'<a href="#" class="expandAll">' . $this->translate('expand all') . '</a>, ' .
					'<a href="#" class="collapseAll">' . $this->translate('collapse all') . '</a>' .
				'</div>' . $this->getNewline() .

//				$this->prependIndentionToEachLine($this->getOwnerPlugin()->createWidget('Messages')->getHtml()) . $this->getNewline() .
			'</div>' . $this->getNewline();
	}

	public function getHtmlForUpdate($finalResult)
	{
		if ($this->getOwnerPlugin()->getOwnerSpec()->getParent())
			return;

		return '<div>' . $this->getOwnerPlugin()->createWidget('finalResult\Update')->getHtml($finalResult) . '</div>';
	}
}