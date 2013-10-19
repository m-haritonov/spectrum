<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components;

class DetailsControl extends Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.c-detailsControl { display: inline-block; }' . $this->getNewline() .

				$this->getIndention() . '.c-detailsControl a { display: inline-block; padding: 0 2px; }' . $this->getNewline() .
				$this->getIndention() . '.c-detailsControl a span { display: inline-block; position: relative; width: 8px; height: 8px; border: 1px solid #bbb; background: #ccc; border-radius: 5px; vertical-align: -1px; }' . $this->getNewline() .
				$this->getIndention() . '.c-detailsControl a.state.selected span { background: #e6932f; border-color: #d4872a; }' . $this->getNewline() .

				$this->getIndention() . '.c-detailsControl a.previous span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }' . $this->getNewline() .

				$this->getIndention() . '.c-detailsControl a.next span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }' . $this->getNewline() .
				$this->getIndention() . '.c-detailsControl a.next span:after { content: "\\0020"; display: block; position: absolute; top: 1px; right: 3px; bottom: 1px; left: 3px; background: #fff; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getScripts()
	{
		return
			'<script type="text/javascript">
				spectrum.tools.addEventListener(document, "DOMContentLoaded", function()
				{
					function clickCurrentState(e)
					{
						e.preventDefault();
						spectrum.tools.removeClass(e.currentTarget.parentNode.querySelectorAll(".state"), "selected");
						spectrum.tools.addClass(e.currentTarget, "selected");
					}

					var detailsControlNodes = document.querySelectorAll(".c-detailsControl");
					for (var i = 0; i < detailsControlNodes.length; i++)
					{
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector(".previous"), "click", function(e){
							e.preventDefault();
							var previousState = e.currentTarget.parentNode.querySelector(".state.selected").previousSibling;
							if (spectrum.tools.hasClass(previousState, "state"))
								spectrum.tools.dispatchEvent(previousState, "click");
						});

						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector(".next"), "click", function(e){
							e.preventDefault();
							var nextState = e.currentTarget.parentNode.querySelector(".state.selected").nextSibling;
							if (spectrum.tools.hasClass(nextState, "state"))
								spectrum.tools.dispatchEvent(nextState, "click");
						});

						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector(".state1"), "click", function(e){
							clickCurrentState(e);

							spectrum.tools.removeClass(".c-specList>li.notEnding", "expand");
							spectrum.tools.removeClass(".c-specList>li.ending", "expand");
							spectrum.tools.removeClass(".c-resultBuffer>.results>.result", "expand");
						});

						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector(".state2"), "click", function(e){
							clickCurrentState(e);

							spectrum.tools.addClass(".c-specList>li.notEnding", "expand");
							spectrum.tools.removeClass(".c-specList>li.ending", "expand");
							spectrum.tools.removeClass(".c-resultBuffer>.results>.result", "expand");
						});

						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector(".state3"), "click", function(e){
							clickCurrentState(e);

							spectrum.tools.addClass(".c-specList>li.notEnding", "expand");
							spectrum.tools.addClass(".c-specList>li.ending", "expand");
							spectrum.tools.removeClass(".c-resultBuffer>.results>.result", "expand");
						});

						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector(".state4"), "click", function(e){
							clickCurrentState(e);

							spectrum.tools.addClass(".c-specList>li.notEnding", "expand");
							spectrum.tools.addClass(".c-specList>li.ending", "expand");
							spectrum.tools.addClass(".c-resultBuffer>.results>.result", "expand");
						});
					}
				});' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtml()
	{
		if ($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->getParentSpecs())
			return;

		return
			'<div class="c-detailsControl">' . $this->getNewline() .
				'<a href="#" class="previous"><span></span></a>' .
				'<a href="#" class="state state1"><span></span></a>' .
				'<a href="#" class="state state2 selected"><span></span></a>' .
				'<a href="#" class="state state3"><span></span></a>' .
				'<a href="#" class="state state4"><span></span></a>' .
				'<a href="#" class="next"><span></span></a>' .
			'</div>' . $this->getNewline();
	}
}