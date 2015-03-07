<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private\reports\html\components;

use spectrum\core\SpecInterface;

class totalResult extends component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-totalResult-result { color: #aaa; font-weight: bold; }
			.app-totalResult-result.fail { color: #a31010; }
			.app-totalResult-result.success { color: #009900; }
			.app-totalResult-result.empty { color: #cc9900; }
		
			.app-totalResult-update { display: none; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @return string
	 */
	static public function getScripts() {
		return static::formatTextForOutput('<script type="text/javascript">/*<![CDATA[*/
			spectrum = window.spectrum || {};
			spectrum.totalResult = {
				update: function() {
					var totalResultNode = spectrum.tools.getExecutingScriptNode();
					while (!spectrum.tools.hasClass(totalResultNode, "app-totalResult-update")) {
						totalResultNode = totalResultNode.parentNode;
					}
		
					var result = totalResultNode.querySelectorAll(".result")[0].innerHTML;
					var title = totalResultNode.querySelectorAll(".title")[0].innerHTML;
					var resultNodes = document.querySelectorAll(".app-totalResult-result." + spectrum.tools.getClassesByPrefix(totalResultNode, "id-")[0]);
					for (var i = 0; i < resultNodes.length; i++) {
						resultNodes[i].className += " " + result;
						resultNodes[i].innerHTML = title;
					}
				}
			};
		/*]]>*/</script>', 2);
	}

	/**
	 * @return string
	 */
	static public function getContent(SpecInterface $spec) {
		return
			'<span class="app-totalResult-result id-' . static::escapeHtml($spec->getRunId()) . '">' .
				static::translateAndEscapeHtml('wait...') .
			'</span>';
	}

	/**
	 * @return string
	 */
	static public function getContentForUpdate(SpecInterface $spec) {
		$resultName = static::getResultName($spec->getResultBuffer()->getTotalResult());
		return
			'<span class="app-totalResult-update id-' . static::escapeHtml($spec->getRunId()) . '">' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<span class="result">' . static::escapeHtml($resultName) . '</span>' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<span class="title">' . static::translateAndEscapeHtml($resultName) . '</span>' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<script type="text/javascript">/*<![CDATA[*/spectrum.totalResult.update();/*]]>*/</script>' . static::getHtmlEscapedOutputNewline() .
			'</span>';
	}

	/**
	 * @param null|bool $result
	 * @return string
	 */
	static protected function getResultName($result) {
		if ($result === false) {
			return 'fail';
		} else if ($result === true) {
			return 'success';
		} else if ($result === null) {
			return 'empty';
		} else {
			return 'unknown';
		}
	}
}