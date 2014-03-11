<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components;

use spectrum\core\SpecInterface;

class detailsControl extends component
{
	static public function getStyles()
	{
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-detailsControl { display: inline-block; }

			.c-detailsControl a { display: inline-block; padding: 0 2px; }
			.c-detailsControl a span { display: inline-block; position: relative; width: 8px; height: 8px; border: 1px solid #bbb; background: #ccc; border-radius: 5px; vertical-align: -1px; }
			.c-detailsControl a.state.selected span { background: #e6932f; border-color: #d4872a; }

			.c-detailsControl a.previous span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }

			.c-detailsControl a.next span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }
			.c-detailsControl a.next span:after { content: "\\0020"; display: block; position: absolute; top: 1px; right: 3px; bottom: 1px; left: 3px; background: #fff; }
		/*]]>*/</style>', 2);
	}

	static public function getScripts()
	{
		return static::formatTextForOutput('<script type="text/javascript">/*<![CDATA[*/
			(function(){
				function changeDetailsControlStates(newStateNumber)
				{
					spectrum.tools.removeClass(document.querySelectorAll(".c-detailsControl a.state"), "selected");
					spectrum.tools.addClass(document.querySelectorAll(".c-detailsControl a.state" + newStateNumber), "selected");
				}
				
				spectrum.tools.addEventListener(document, "DOMContentLoaded", function()
				{
					var detailsControlNodes = document.querySelectorAll(".c-detailsControl");
					for (var i = 0; i < detailsControlNodes.length; i++)
					{
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector(".previous"), "click", function(e){
							e.preventDefault();
							var previousState = e.currentTarget.parentNode.querySelector("a.state.selected").previousSibling;
							if (spectrum.tools.hasClass(previousState, "state"))
								spectrum.tools.dispatchEvent(previousState, "click");
						});
	
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector(".next"), "click", function(e){
							e.preventDefault();
							var nextState = e.currentTarget.parentNode.querySelector("a.state.selected").nextSibling;
							if (spectrum.tools.hasClass(nextState, "state"))
								spectrum.tools.dispatchEvent(nextState, "click");
						});
	
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector("a.state1"), "click", function(e){
							e.preventDefault();
							changeDetailsControlStates(1);
	
							spectrum.tools.removeClass(".c-specList>li.notEnding", "expand");
							spectrum.tools.removeClass(".c-specList>li.ending", "expand");
							spectrum.tools.removeClass(".c-resultBuffer>.results>.result", "expand");
						});
	
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector("a.state2"), "click", function(e){
							e.preventDefault();
							changeDetailsControlStates(2);
	
							spectrum.tools.addClass(".c-specList>li.notEnding", "expand");
							spectrum.tools.removeClass(".c-specList>li.ending", "expand");
							spectrum.tools.removeClass(".c-resultBuffer>.results>.result", "expand");
						});
	
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector("a.state3"), "click", function(e){
							e.preventDefault();
							changeDetailsControlStates(3);
	
							spectrum.tools.addClass(".c-specList>li.notEnding", "expand");
							spectrum.tools.addClass(".c-specList>li.ending", "expand");
							spectrum.tools.removeClass(".c-resultBuffer>.results>.result", "expand");
						});
	
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector("a.state4"), "click", function(e){
							e.preventDefault();
							changeDetailsControlStates(4);
	
							spectrum.tools.addClass(".c-specList>li.notEnding", "expand");
							spectrum.tools.addClass(".c-specList>li.ending", "expand");
							spectrum.tools.addClass(".c-resultBuffer>.results>.result", "expand");
						});
					}
				});
			})();
		/*]]>*/</script>', 2);
	}

	static public function getHtml(SpecInterface $spec)
	{
		if ($spec->getParentSpecs())
			return null;

		return
			'<div class="c-detailsControl">' .
				'<a href="#" class="previous"><span></span></a>' .
				'<a href="#" class="state state1"><span></span></a>' .
				'<a href="#" class="state state2"><span></span></a>' .
				'<a href="#" class="state state3 selected"><span></span></a>' .
				'<a href="#" class="state state4"><span></span></a>' .
				'<a href="#" class="next"><span></span></a>' .
			'</div>';
	}
}