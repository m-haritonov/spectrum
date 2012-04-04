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

namespace net\mkharitonov\spectrum\reports\widgets;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class TotalInfo extends \net\mkharitonov\spectrum\reports\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-totalInfo { margin: 1em 0; padding: 6px 10px; border-radius: 4px; background: #ddd; }' . $this->getNewline() .
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
					var totalInfo = document.querySelector(".g-totalInfo");
					totalInfo.querySelector(".specChildren .expandAll").addEventListener("click", function(e){
						e.preventDefault();
						tools.addClass(".g-specList>li.container", "expand");
					});

					totalInfo.querySelector(".specChildren .collapseAll").addEventListener("click", function(e){
						e.preventDefault();
						tools.removeClass(".g-specList>li.container", "expand");
					});

					/**/

					totalInfo.querySelector(".specDetails .expandAll").addEventListener("click", function(e){
						e.preventDefault();
						tools.addClass(".g-specList>li.item", "expand");
					});

					totalInfo.querySelector(".specDetails .collapseAll").addEventListener("click", function(e){
						e.preventDefault();
						tools.removeClass(".g-specList>li.item", "expand");
					});

					/**/

					totalInfo.querySelector(".resultDetails .expandAll").addEventListener("click", function(e){
						e.preventDefault();
						tools.addClass(".g-runResultsBuffer>.results>.result", "expand");
					});

					totalInfo.querySelector(".resultDetails .collapseAll").addEventListener("click", function(e){
						e.preventDefault();
						tools.removeClass(".g-runResultsBuffer>.results>.result", "expand");
					});
				});' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtml()
	{
		$spec = $this->getOwnerPlugin()->getOwnerSpec();

		if ($spec->getParent())
			return;


		return
			'<div class="g-totalInfo">' . $this->getNewline() .
				'<span class="result">' . $this->getNewline() .
					'<h1>' . $this->translate('Total result') . ':</h1>' . $this->getNewline() .
					$this->prependIndentionToEachLine($this->getOwnerPlugin()->createWidget('finalResult\Result')->getHtml()) . $this->getNewline() .
				'</span> | ' . $this->getNewline() .

				'<span class="specChildren">' .
					'Spec children: ' .
					'<a href="#" class="expandAll">' . $this->translate('expand all') . '</a>, ' .
					'<a href="#" class="collapseAll">' . $this->translate('collapse all') . '</a>' .
				'</span> | ' . $this->getNewline() .

				'<span class="specDetails">' .
					'Spec details: ' .
					'<a href="#" class="expandAll">' . $this->translate('expand all') . '</a>, ' .
					'<a href="#" class="collapseAll">' . $this->translate('collapse all') . '</a>' .
				'</span> | ' . $this->getNewline() .

				'<span class="resultDetails">' .
					'Result details: ' .
					'<a href="#" class="expandAll">' . $this->translate('expand all') . '</a>, ' .
					'<a href="#" class="collapseAll">' . $this->translate('collapse all') . '</a>' .
				'</span>' . $this->getNewline() .

//				$this->prependIndentionToEachLine($this->getOwnerPlugin()->createWidget('Messages')->getHtml()) . $this->getNewline() .
			'</div>' . $this->getNewline();
	}
}