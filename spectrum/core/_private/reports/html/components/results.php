<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\html\components;

use spectrum\core\config;
use spectrum\core\models\details\MatcherCallInterface;
use spectrum\core\models\details\PhpErrorInterface;
use spectrum\core\models\details\UserFailInterface;
use spectrum\core\models\ResultInterface;
use spectrum\core\models\SpecInterface;

class results extends \spectrum\core\_private\reports\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-results { position: relative; margin: 0.5em 0 1em 0; }
			.app-results>h1 { float: left; margin-bottom: 2px; padding: 0.3em 0.5em 0 0; color: #888; font-size: 0.9em; font-weight: normal; }

			.app-results>.results { clear: both; }
			.app-results>.results>.result { float: left; position: relative; margin: 0 2px 2px 0; border: 1px solid; border-left: 0; border-top: 0; border-radius: 4px; white-space: nowrap; }
			.app-results>.results>.result>a.expand { float: left; position: relative; overflow: hidden; width: 19px; height: 0; padding-top: 1.6em; margin-right: 2px; border-radius: 4px 0 4px 0; font-size: 0.9em; font-weight: bold; text-decoration: none; text-align: center; }
			.app-results>.results>.result>a.expand:before { content: "\\0020"; display: block; position: absolute; top: 8px; left: 6px; width: 8px; height: 2px; background: #fff; }
			.app-results>.results>.result>a.expand:after { content: "\\0020"; display: block; position: absolute; top: 5px; left: 9px; width: 2px; height: 8px; background: #fff; }
			.app-results>.results>.result>.num { float: left; margin-right: 2px; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }
			.app-results>.results>.result>.value { float: left; margin-right: 2px; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }
			.app-results>.results>.result>.type { float: left; margin-right: 1px; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }
			.app-results>.results>.result>.details { clear: both; }

			.app-results>.results>.result { border-color: #e0e0e0; background: #f3f3f3; }
			.app-results>.results>.result>.num,
			.app-results>.results>.result>.value,
			.app-results>.results>.result>.type { background: #e0e0e0; color: #3b3b3b; }
			.app-results>.results>.result>a.expand { background: #d9d9d9; color: #ffffff; }
			
			.app-results>.results>.result.fail { border-color: #e2b5b5; background: #ffcccc; }
			.app-results>.results>.result.fail>.num,
			.app-results>.results>.result.fail>.value,
			.app-results>.results>.result.fail>.type { background: #e2b5b5; color: #3d3232; }
			.app-results>.results>.result.fail>a.expand { background: #db9a9a; color: #ffe3db; }
			
			.app-results>.results>.result.success { border-color: #b5dfb5; background: #ccffcc; }
			.app-results>.results>.result.success>.num,
			.app-results>.results>.result.success>.value,
			.app-results>.results>.result.success>.type { background: #b5dfb5; color: #3a473a; }
			.app-results>.results>.result.success>a.expand { background: #85cc8c; color: #e4ffe0; }
			
			.app-results>.results>.result.empty { border-color: #e0d9b6; background: #fff7cc; }
			.app-results>.results>.result.empty>.num { background: #e0d9b6; color: #3b3930; }
			.app-results>.results>.result.empty>.value,
			.app-results>.results>.result.empty>.type { background: #e0d9b6; color: #3b3930; }
			.app-results>.results>.result.empty>a.expand { background: #d9ce9a; color: #fdffdb; }
			
			.app-results>.results>.result.expanded>a.expand:after { display: none; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @return string
	 */
	static public function getScripts() {
		return static::formatTextForOutput('<script type="text/javascript">/*<![CDATA[*/
			(function(){
				function toggleExpand(resultsNode) {
					if (spectrum.tools.hasClass(resultsNode, "expanded")) {
						spectrum.tools.removeClass(resultsNode, "expanded");
					} else {
						spectrum.tools.addClass(resultsNode, "expanded");
					} 
				}
				
				spectrum.tools.addEventListener(document, "DOMContentLoaded", function() {
					var resultsNodes = document.body.querySelectorAll(".app-results>.results>.result");
					for (var i = 0; i < resultsNodes.length; i++) {
						spectrum.tools.addEventListener(resultsNodes[i], "click", function(e){
							e.preventDefault();
							
							// Uses middle click instead of double click for text selection by double click support
							if (e.button == 1) {
								toggleExpand(e.currentTarget);
							}
						});
	
						spectrum.tools.addEventListener(resultsNodes[i].querySelector("a.expand"), "click", function(e){
							e.preventDefault();
							toggleExpand(e.currentTarget.parentNode);
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
		$contentForResults = static::getContentForResults($spec->getResults()->getAll());
		
		if (trim($contentForResults) == '') {
			return null;
		}
		
		$content = '';
		$content .= '<div class="app-results app-clearFix">';
		$content .= '<h1>' . static::translateAndEscapeHtml('Results') . ':</h1>';
		$content .= $contentForResults;
		$content .= '</div>';
		return $content;
	}

	/**
	 * @param ResultInterface[] $results
	 * @return string
	 */
	static protected function getContentForResults(array $results) {
		$content = '';
		
		$num = 0;
		foreach ($results as $result) {
			$num++;
			$value = $result->getValue();
			
			if (!(($value === false && config::hasOutputResults('all fail')) || ($value === true && config::hasOutputResults('all success')) || ($value === null && config::hasOutputResults('all empty')) || ($value !== false && $value !== true && $value !== null && config::hasOutputResults('all unknown')))) {
				continue;
			}
			
			$content .= '<div class="result ' . static::getResultValueName($value) . '">';
			$content .= '<a href="#" class="expand" title="' . static::translateAndEscapeHtml('Show/hide full details (also available by mouse middle click on the card)') . '">' . static::translateAndEscapeHtml('Expand/collapse') . '</a>';
			$content .= '<div class="num" title="' . static::translateAndEscapeHtml('Order') . '">' . static::translateAndEscapeHtml('No.') . ' ' . $num . '</div>';
			$content .= '<div class="value" title="' . static::translateAndEscapeHtml('Result') . '">' . static::escapeHtml(static::getResultValueName($value)) . '</div>';
			$content .= '<div class="type" title="' . static::translateAndEscapeHtml('Type') . '">' . static::translateAndEscapeHtml(static::getType($result->getDetails())) . '</div>';
			$content .= '<div class="details">';
			$content .= static::getContentForResultDetails($result->getDetails());
			$content .= '</div>';
			$content .= '</div>';
		}

		if ($content != '') {
			return '<div class="results">' . $content . '</div>';
		} else {
			return null;
		}
	}

	/**
	 * @param mixed $result
	 * @return string
	 */
	static protected function getResultValueName($result) {
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

	/**
	 * @param mixed $details
	 * @return string
	 */
	static protected function getType($details) {
		if (is_object($details) && $details instanceof MatcherCallInterface) {
			return 'matcher call';
		} else if (is_object($details) && $details instanceof PhpErrorInterface) {
			return 'php error';
		} else if (is_object($details) && $details instanceof UserFailInterface) {
			return 'user fail';
		} else if (is_object($details) && $details instanceof \Exception) {
			return 'exception';
		} else {
			return 'unknown';
		}
	}

	/**
	 * @param mixed $details
	 * @return string
	 */
	static protected function getContentForResultDetails($details) {
		if (is_object($details) && $details instanceof MatcherCallInterface) {
			return static::callComponentMethod('details\matcherCall', 'getContent', array($details));
		} else if (is_object($details) && $details instanceof PhpErrorInterface) {
			return static::callComponentMethod('details\phpError', 'getContent', array($details));
		} else if (is_object($details) && $details instanceof UserFailInterface) {
			return static::callComponentMethod('details\userFail', 'getContent', array($details));
		} else {
			return static::callComponentMethod('details\unknown', 'getContent', array($details));
		}
	}
}