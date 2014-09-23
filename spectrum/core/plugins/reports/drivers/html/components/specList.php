<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components;

use spectrum\core\SpecInterface;

class specList extends component {
	static protected $depth;
	static protected $numbers = array();

	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-specList { padding-right: 35px; list-style: none; }
			.c-specList>li { margin-top: 3px; white-space: nowrap; }
			.c-specList>li .indention { display: inline-block; width: 0; white-space: pre; }
			.c-specList>li>.head { display: inline-block; }
			.c-specList>li>.head>.point { position: relative; padding: 1px 16px 1px 6px; border-radius: 20px; background: #e5e5e5; }
			.c-specList>li>.head>.point>.number { font-size: 0.9em; }
			.c-specList>li>.head>.point>.number .dot { display: inline-block; width: 0; color: transparent; }
			.c-specList>li>.head>.point>a.expand { display: block; position: absolute; top: 0; right: 0; bottom: 0; left: 0; padding-right: 2px; text-decoration: none; text-align: right; }
			.c-specList>li>.head>.point>a.expand span { display: inline-block; overflow: hidden; position: relative; width: 8px; height: 0; padding-top: 8px; border: 1px solid #bbb; background: #ccc; border-radius: 5px; vertical-align: middle; }
			.c-specList>li>.head>.point>a.expand span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }
			.c-specList>li>.head>.point>a.expand span:after { content: "\\0020"; display: block; position: absolute; top: 1px; right: 3px; bottom: 1px; left: 3px; background: #fff; }
			.c-specList>li>.head>.title { display: inline-block; vertical-align: text-top; white-space: normal; }
			
			.c-specList>li>.head>.title>.separator span,
			.c-specList>li>.body>.title>.separator span { display: inline-block; width: 0; color: transparent; }
			.c-specList>li>.head>.title>.separator:before,
			.c-specList>li>.body>.title>.separator:before { content: "\\2014"; display: inline; }
		
			.c-specList>li>.c-specList { padding-right: 0; }
		
			.c-specList>li.notEnding>.c-specList { display: none; margin-left: 30px; white-space: normal; }
			.c-specList>li.notEnding>.c-specList>li { position: relative; }
			.c-specList>li.notEnding>.c-specList>li:before { content: "\\0020"; display: block; position: absolute; top: -3px; bottom: 0; left: -18px; width: 1px; background: #ccc; }
			.c-specList>li.notEnding>.c-specList>li:after { content: "\\0020"; display: block; position: absolute; top: 8px; left: -17px; width: 17px; height: 1px; background: #ccc; }
			.c-specList>li.notEnding>.c-specList>li:last-child:before { bottom: auto; height: 12px; }
		
			.c-specList>li.ending>.body { display: inline-block; vertical-align: text-top; white-space: normal; }
			.c-specList>li.ending>.body>.runDetails { display: none; }
			
			.c-specList>li.noContent>.head>.point>a.expand { display: none; }
			.c-specList>li.noContent>.head>.point { padding-right: 6px; }

			.c-specList>li.expanded.notEnding>.head { position: relative; }
			.c-specList>li.expanded.notEnding>.head:before { content: "\\0020"; display: block; position: absolute; top: 0; bottom: 0; left: 12px; width: 1px; background: #ccc; }
			.c-specList>li.expanded.notEnding>.c-specList { display: block; }
			.c-specList>li.expanded.ending>.body>.runDetails { display: block; }
			.c-specList>li.expanded>.head>.point>a.expand span:after { display: none; }
		/*]]>*/</style>', 2);
	}

	static public function getScripts() {
		return static::formatTextForOutput('<script type="text/javascript">/*<![CDATA[*/
			spectrum.tools.addEventListener(document, "DOMContentLoaded", function() {
				var expandLinkNodes = document.body.querySelectorAll(".c-specList>li>.head>.point>a.expand");
				for (var i = 0; i < expandLinkNodes.length; i++) {
					var liNode = expandLinkNodes[i].parentNode.parentNode.parentNode;

					if (liNode.querySelector(".runDetails, .c-specList") == null) {
						spectrum.tools.addClass(liNode, "noContent");
					}

					spectrum.tools.addEventListener(expandLinkNodes[i], "click", function(e){
						e.preventDefault();
						var liNode = e.currentTarget.parentNode.parentNode.parentNode;

						if (spectrum.tools.hasClass(liNode, "expanded")) {
							spectrum.tools.removeClass(liNode, "expanded");
						} else {
							spectrum.tools.addClass(liNode, "expanded");
						}
					});
				}
			});
		/*]]>*/</script>', 2);
	}

	static public function getContentBegin(SpecInterface $spec) {
		$content = '';
		
		if (!$spec->getParentSpecs()) {
			static::$depth = 0;
			$content .= static::getHtmlEscapedOutputIndention(static::$depth) . '<ol class="c-specList">';
		} else if (!$spec->isAnonymous()) {
			if (!isset(static::$numbers[static::$depth])) {
				static::$numbers[static::$depth] = 0;
			}
			
			static::$numbers[static::$depth]++;

			$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 1) . '<li class="' . ($spec->getChildSpecs() ? 'notEnding' : 'ending') . ' expanded" id="' . static::escapeHtml($spec->getRunId()) . '">' . static::getHtmlEscapedOutputNewline();
			
			if ($spec->getChildSpecs()) {
				$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 2) . '<div class="head">' . static::getHtmlEscapedOutputNewline();
				$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getContentForSpecPoint(), static::$depth * 2 + 3) . static::getHtmlEscapedOutputNewline();
				$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getContentForSpecTitle($spec), static::$depth * 2 + 3) . static::getHtmlEscapedOutputNewline();
				$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 2) . '</div>' . static::getHtmlEscapedOutputNewline();
				$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 2) . '<ol class="c-specList">';
				static::$depth++;
			} else {
				$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 2) . '<div class="head">' . static::getHtmlEscapedOutputNewline();
				$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getContentForSpecPoint(), static::$depth * 2 + 3) . static::getHtmlEscapedOutputNewline();
				$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 2) . '</div>' . static::getHtmlEscapedOutputNewline();
				$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 2) . '<div class="body">' . static::getHtmlEscapedOutputNewline();
				$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getContentForSpecTitle($spec), static::$depth * 2 + 3);
			}
		}

		return $content;
	}

	static public function getContentEnd(SpecInterface $spec) {
		$content = '';
		
		if ($spec->getParentSpecs()) {
			if (!$spec->isAnonymous()) {
				if ($spec->getChildSpecs()) {
					static::$numbers[static::$depth] = 0;
					static::$depth--;
					$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 2) . '</ol>' . static::getHtmlEscapedOutputNewline();
				} else {
					$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getContentForRunDetails($spec), static::$depth * 2 + 3) . static::getHtmlEscapedOutputNewline();
					$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 2) . '</div>' . static::getHtmlEscapedOutputNewline();
				}

				$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::callComponentMethod('totalResult', 'getContentForUpdate', array($spec)), static::$depth * 2 + 2) . static::getHtmlEscapedOutputNewline();
				$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 1) . '</li>';
			}
		}
		else {
			$content .= static::getHtmlEscapedOutputIndention(static::$depth) . '</ol>';
		}

		return $content;
	}

	static protected function getContentForSpecPoint() {
		return
			'<span class="point">' .
				// Indention should be copied to buffer
				str_repeat('<span class="indention">' . static::getHtmlEscapedOutputIndention() . '</span>', static::$depth) .
				'<span class="number">' .
					static::escapeHtml(isset(static::$numbers[static::$depth]) ? static::$numbers[static::$depth] : '') .
					'<span class="dot">.</span>' .
				'</span>' .
				'<a href="#" class="expand" title="' . static::translateAndEscapeHtml('Expand/collapse child content') . '"><span></span></a>' .
			'</span> ';
	}

	static protected function getContentForSpecTitle(SpecInterface $spec) {
		return
			'<span class="title">' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<span class="name">' . static::escapeHtml(static::convertToOutputCharset($spec->getName())) . '</span> ' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<span class="separator"><span>-</span></span> ' . static::getHtmlEscapedOutputNewline() .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::callComponentMethod('totalResult', 'getContent', array($spec))) . static::getHtmlEscapedOutputNewline() .
			'</span>';
	}

	static protected function getContentForRunDetails(SpecInterface $spec) {
		$componentResults = array();
		if ($spec->getResultBuffer()->getTotalResult() !== true) {
			$componentResults[] = static::callComponentMethod('resultBuffer', 'getContent', array($spec));
		}

		$componentResults[] = static::callComponentMethod('messages', 'getContent', array($spec));

		$content = '';
		foreach ($componentResults as $result) {
			if (trim($result) != '') {
				$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline($result) . static::getHtmlEscapedOutputNewline();
			}
		}
		
		if ($content != '') {
			return
				'<div class="runDetails c-clearFix">' . static::getHtmlEscapedOutputNewline() .
					$content .
				'</div>';
		}
		
		return null;
	}
}