<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets;

class DetailsControl extends \spectrum\reports\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-detailsControl { display: inline-block; }' . $this->getNewline() .

				$this->getIndention() . '.g-detailsControl a { display: inline-block; padding: 0 2px; }' . $this->getNewline() .
				$this->getIndention() . '.g-detailsControl a span { display: inline-block; position: relative; width: 8px; height: 8px; border: 1px solid #bbb; background: #ccc; border-radius: 5px; vertical-align: -1px; }' . $this->getNewline() .
				$this->getIndention() . '.g-detailsControl a.state.selected span { background: #e6932f; border-color: #d4872a; }' . $this->getNewline() .

				$this->getIndention() . '.g-detailsControl a.previous span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }' . $this->getNewline() .

				$this->getIndention() . '.g-detailsControl a.next span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }' . $this->getNewline() .
				$this->getIndention() . '.g-detailsControl a.next span:after { content: "\\0020"; display: block; position: absolute; top: 1px; right: 3px; bottom: 1px; left: 3px; background: #fff; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getScripts()
	{
		return
			'<script type="text/javascript">
				document.addEventListener("DOMContentLoaded", function()
				{
					function clickCurrentState(e)
					{
						e.preventDefault();
						tools.removeClass(e.currentTarget.parentNode.querySelectorAll(".state"), "selected");
						tools.addClass(e.currentTarget, "selected");
					}

					var nodes = document.querySelectorAll(".g-detailsControl");
					for (var i = 0; i < nodes.length; i++)
					{
						var node = nodes[i];

						node.querySelector(".previous").addEventListener("click", function(e){
							e.preventDefault();
							var previousState = e.currentTarget.parentNode.querySelector(".state.selected").previousSibling;
							if (tools.hasClass(previousState, "state"))
								tools.dispatchEvent("click", previousState);
						});

						node.querySelector(".next").addEventListener("click", function(e){
							e.preventDefault();
							var nextState = e.currentTarget.parentNode.querySelector(".state.selected").nextSibling;
							if (tools.hasClass(nextState, "state"))
								tools.dispatchEvent("click", nextState);
						});

						/**/

						node.querySelector(".state1").addEventListener("click", function(e){
							clickCurrentState(e);

							tools.removeClass(".g-specList>li.container", "expand");
							tools.removeClass(".g-specList>li.item", "expand");
							tools.removeClass(".g-runResultsBuffer>.results>.result", "expand");
						});

						node.querySelector(".state2").addEventListener("click", function(e){
							clickCurrentState(e);

							tools.addClass(".g-specList>li.container", "expand");
							tools.removeClass(".g-specList>li.item", "expand");
							tools.removeClass(".g-runResultsBuffer>.results>.result", "expand");
						});

						node.querySelector(".state3").addEventListener("click", function(e){
							clickCurrentState(e);

							tools.addClass(".g-specList>li.container", "expand");
							tools.addClass(".g-specList>li.item", "expand");
							tools.removeClass(".g-runResultsBuffer>.results>.result", "expand");
						});

						node.querySelector(".state4").addEventListener("click", function(e){
							clickCurrentState(e);

							tools.addClass(".g-specList>li.container", "expand");
							tools.addClass(".g-specList>li.item", "expand");
							tools.addClass(".g-runResultsBuffer>.results>.result", "expand");
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
			'<div class="g-detailsControl">' . $this->getNewline() .
				'<a href="#" class="previous"><span></span></a>' .
				'<a href="#" class="state state1"><span></span></a>' .
				'<a href="#" class="state state2 selected"><span></span></a>' .
				'<a href="#" class="state state3"><span></span></a>' .
				'<a href="#" class="state state4"><span></span></a>' .
				'<a href="#" class="next"><span></span></a>' .
			'</div>' . $this->getNewline();
	}
}