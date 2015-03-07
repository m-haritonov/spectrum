<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private\reports\html\components;

use spectrum\core\SpecInterface;

class detailsControl extends component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-detailsControl { display: inline-block; }

			.app-detailsControl a { display: inline-block; padding: 0 2px; }
			.app-detailsControl a span { display: inline-block; overflow: hidden; position: relative; width: 8px; height: 0; padding-top: 8px; border: 1px solid #bbb; background: #ccc; border-radius: 5px; vertical-align: -1px; }
			.app-detailsControl a.state.selected span { background: #e6932f; border-color: #d4872a; }

			.app-detailsControl a.previous span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }

			.app-detailsControl a.next span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }
			.app-detailsControl a.next span:after { content: "\\0020"; display: block; position: absolute; top: 1px; right: 3px; bottom: 1px; left: 3px; background: #fff; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @return string
	 */
	static public function getScripts() {
		return static::formatTextForOutput('<script type="text/javascript">/*<![CDATA[*/
			(function(){
				function changeDetailsControlStates(newStateNumber) {
					spectrum.tools.removeClass(document.querySelectorAll(".app-detailsControl a.state"), "selected");
					spectrum.tools.addClass(document.querySelectorAll(".app-detailsControl a.state" + newStateNumber), "selected");
				}
				
				spectrum.tools.addEventListener(document, "DOMContentLoaded", function() {
					var detailsControlNodes = document.querySelectorAll(".app-detailsControl");
					for (var i = 0; i < detailsControlNodes.length; i++) {
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector(".previous"), "click", function(e){
							e.preventDefault();
							var previousState = e.currentTarget.parentNode.querySelector("a.state.selected").previousSibling;
							if (spectrum.tools.hasClass(previousState, "state")) {
								spectrum.tools.dispatchEvent(previousState, "click");
							}
						});
	
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector("a.state1"), "click", function(e){
							e.preventDefault();
							changeDetailsControlStates(1);
	
							spectrum.tools.removeClass(".app-specList>li.notEnding", "expanded");
							spectrum.tools.removeClass(".app-specList>li.ending", "expanded");
							spectrum.tools.removeClass(".app-resultBuffer>.results>.result", "expanded");
						});
	
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector("a.state2"), "click", function(e){
							e.preventDefault();
							changeDetailsControlStates(2);
	
							spectrum.tools.addClass(".app-specList>li.notEnding", "expanded");
							spectrum.tools.removeClass(".app-specList>li.ending", "expanded");
							spectrum.tools.removeClass(".app-resultBuffer>.results>.result", "expanded");
						});
	
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector("a.state3"), "click", function(e){
							e.preventDefault();
							changeDetailsControlStates(3);
	
							spectrum.tools.addClass(".app-specList>li.notEnding", "expanded");
							spectrum.tools.addClass(".app-specList>li.ending", "expanded");
							spectrum.tools.removeClass(".app-resultBuffer>.results>.result", "expanded");
						});
	
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector("a.state4"), "click", function(e){
							e.preventDefault();
							changeDetailsControlStates(4);
	
							spectrum.tools.addClass(".app-specList>li.notEnding", "expanded");
							spectrum.tools.addClass(".app-specList>li.ending", "expanded");
							spectrum.tools.addClass(".app-resultBuffer>.results>.result", "expanded");
						});
						
						spectrum.tools.addEventListener(detailsControlNodes[i].querySelector(".next"), "click", function(e){
							e.preventDefault();
							var nextState = e.currentTarget.parentNode.querySelector("a.state.selected").nextSibling;
							if (spectrum.tools.hasClass(nextState, "state")) {
								spectrum.tools.dispatchEvent(nextState, "click");
							}
						});
					}
				});
			})();
		/*]]>*/</script>', 2);
	}

	/**
	 * @return null|string
	 */
	static public function getContent(SpecInterface $spec) {
		if ($spec->getParentSpecs()) {
			return null;
		}
		
		return
			'<div class="app-detailsControl">' .
				'<a href="#" class="previous"><span>' . static::translateAndEscapeHtml('Previous') . '</span></a>' .
				'<a href="#" class="state state1" title="' . static::translateAndEscapeHtml('Collapse all') . '"><span>' . static::translateAndEscapeHtml('State 1') . '</span></a>' .
				'<a href="#" class="state state2" title="' . static::translateAndEscapeHtml('Expand groups, collapse tests and results') . '"><span>' . static::translateAndEscapeHtml('State 2') . '</span></a>' .
				'<a href="#" class="state state3 selected" title="' . static::translateAndEscapeHtml('Expand groups and tests, collapse results') . '"><span>' . static::translateAndEscapeHtml('State 3') . '</span></a>' .
				'<a href="#" class="state state4" title="' . static::translateAndEscapeHtml('Expand all') . '"><span>' . static::translateAndEscapeHtml('State 4') . '</span></a>' .
				'<a href="#" class="next"><span>' . static::translateAndEscapeHtml('Next') . '</span></a>' .
			'</div>';
	}
}