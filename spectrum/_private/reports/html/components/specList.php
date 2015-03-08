<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private\reports\html\components;

use spectrum\core\SpecInterface;

class specList extends component {
	/**
	 * @var int
	 */
	static protected $depth;

	/**
	 * @var array
	 */
	static protected $numbers = array();

	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-specList { padding-right: 35px; list-style: none; }
			.app-specList>li { margin-top: 3px; white-space: nowrap; }
			.app-specList>li .indention { display: inline-block; width: 0; white-space: pre; }
			.app-specList>li>.head { display: inline-block; }
			.app-specList>li>.head>.point { position: relative; padding: 1px 16px 1px 6px; border-radius: 20px; background: #e5e5e5; }
			.app-specList>li>.head>.point>.number { font-size: 0.9em; }
			.app-specList>li>.head>.point>.number .dot { display: inline-block; width: 0; color: transparent; }
			.app-specList>li>.head>.point>a.expand { display: block; position: absolute; top: 0; right: 0; bottom: 0; left: 0; padding-right: 2px; text-decoration: none; text-align: right; }
			.app-specList>li>.head>.point>a.expand span { display: inline-block; overflow: hidden; position: relative; width: 8px; height: 0; padding-top: 8px; border: 1px solid #bbb; background: #ccc; border-radius: 5px; vertical-align: middle; }
			.app-specList>li>.head>.point>a.expand span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }
			.app-specList>li>.head>.point>a.expand span:after { content: "\\0020"; display: block; position: absolute; top: 1px; right: 3px; bottom: 1px; left: 3px; background: #fff; }
			.app-specList>li>.head>.title { display: inline-block; vertical-align: text-top; white-space: normal; }
			
			.app-specList>li>.head>.title>.separator span,
			.app-specList>li>.body>.title>.separator span { display: inline-block; width: 0; color: transparent; }
			.app-specList>li>.head>.title>.separator:before,
			.app-specList>li>.body>.title>.separator:before { content: "\\2014"; display: inline; }
		
			.app-specList>li>.app-specList { padding-right: 0; }
		
			.app-specList>li.notEnding>.app-specList { display: none; margin-left: 30px; white-space: normal; }
			.app-specList>li.notEnding>.app-specList>li { position: relative; }
			.app-specList>li.notEnding>.app-specList>li:before { content: "\\0020"; display: block; position: absolute; top: -3px; bottom: 0; left: -18px; width: 1px; background: #ccc; }
			.app-specList>li.notEnding>.app-specList>li:after { content: "\\0020"; display: block; position: absolute; top: 8px; left: -17px; width: 17px; height: 1px; background: #ccc; }
			.app-specList>li.notEnding>.app-specList>li:last-child:before { bottom: auto; height: 12px; }
		
			.app-specList>li.ending>.body { display: inline-block; vertical-align: text-top; white-space: normal; }
			.app-specList>li.ending>.body>.runDetails { display: none; }
			
			.app-specList>li.noContent>.head>.point>a.expand { display: none; }
			.app-specList>li.noContent>.head>.point { padding-right: 6px; }

			.app-specList>li.expanded.notEnding>.head { position: relative; }
			.app-specList>li.expanded.notEnding>.head:before { content: "\\0020"; display: block; position: absolute; top: 0; bottom: 0; left: 12px; width: 1px; background: #ccc; }
			.app-specList>li.expanded.notEnding>.app-specList { display: block; }
			.app-specList>li.expanded.ending>.body>.runDetails { display: block; }
			.app-specList>li.expanded>.head>.point>a.expand span:after { display: none; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @return string
	 */
	static public function getScripts() {
		return static::formatTextForOutput('<script type="text/javascript">/*<![CDATA[*/
			spectrum.tools.addEventListener(document, "DOMContentLoaded", function() {
				var expandLinkNodes = document.body.querySelectorAll(".app-specList>li>.head>.point>a.expand");
				for (var i = 0; i < expandLinkNodes.length; i++) {
					var liNode = expandLinkNodes[i].parentNode.parentNode.parentNode;

					if (liNode.querySelector(".runDetails, .app-specList") == null) {
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

	/**
	 * @return string
	 */
	static public function getContentBegin(SpecInterface $spec) {
		$content = '';
		
		if (!$spec->getParentSpecs()) {
			static::$depth = 0;
			$content .= static::getHtmlEscapedOutputIndention(static::$depth) . '<ol class="app-specList">';
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
				$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 2) . '<ol class="app-specList">';
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

	/**
	 * @return string
	 */
	static public function getContentEnd(SpecInterface $spec) {
		$content = '';
		
		if ($spec->getParentSpecs()) {
			if (!$spec->isAnonymous()) {
				if ($spec->getChildSpecs()) {
					static::$numbers[static::$depth] = 0;
					static::$depth--;
					$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 2) . '</ol>' . static::getHtmlEscapedOutputNewline();
				} else {
					$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 2) . static::getContentForRunDetails($spec) . static::getHtmlEscapedOutputNewline();
					$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 2) . '</div>' . static::getHtmlEscapedOutputNewline();
				}

				$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::callComponentMethod('totalResult', 'getContentForUpdate', array($spec)), static::$depth * 2 + 2) . static::getHtmlEscapedOutputNewline();
				$content .= static::getHtmlEscapedOutputIndention(static::$depth * 2 + 1) . '</li>';
			}
		} else {
			$content .= static::getHtmlEscapedOutputIndention(static::$depth) . '</ol>';
		}

		return $content;
	}

	/**
	 * @return string
	 */
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

	/**
	 * @return string
	 */
	static protected function getContentForSpecTitle(SpecInterface $spec) {
		return
			'<span class="title">' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<span class="name">' . static::escapeHtml(static::convertToOutputCharset($spec->getName())) . '</span> ' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<span class="separator"><span>-</span></span> ' . static::getHtmlEscapedOutputNewline() .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::callComponentMethod('totalResult', 'getContent', array($spec))) . static::getHtmlEscapedOutputNewline() .
			'</span>';
	}

	/**
	 * @return null|string
	 */
	static protected function getContentForRunDetails(SpecInterface $spec) {
		$componentResults = array();
		$componentResults[] = static::callComponentMethod('results', 'getContent', array($spec));
		$componentResults[] = static::callComponentMethod('messages', 'getContent', array($spec));

		$content = '';
		foreach ($componentResults as $result) {
			if (trim($result) != '') {
				$content .= $result;
			}
		}
		
		if ($content != '') {
			return
				'<div class="runDetails app-clearFix">' .
					$content .
				'</div>';
		}
		
		return null;
	}
}