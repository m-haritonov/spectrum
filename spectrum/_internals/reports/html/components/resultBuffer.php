<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals\reports\html\components;

use spectrum\config;
use spectrum\core\details\MatcherCallInterface;
use spectrum\core\details\PhpErrorInterface;
use spectrum\core\details\UserFailInterface;
use spectrum\core\SpecInterface;

class resultBuffer extends \spectrum\_internals\reports\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-resultBuffer { position: relative; margin: 0.5em 0 1em 0; }
			.app-resultBuffer>h1 { float: left; margin-bottom: 2px; padding: 0.3em 0.5em 0 0; color: #888; font-size: 0.9em; font-weight: normal; }

			.app-resultBuffer>.results { clear: both; }
			.app-resultBuffer>.results>.result { float: left; position: relative; margin: 0 2px 2px 0; border: 1px solid; border-left: 0; border-top: 0; border-radius: 4px; white-space: nowrap; }
			.app-resultBuffer>.results>.result>a.expand { float: left; position: relative; overflow: hidden; width: 19px; height: 0; padding-top: 1.6em; margin-right: 2px; border-radius: 4px 0 4px 0; font-size: 0.9em; font-weight: bold; text-decoration: none; text-align: center; }
			.app-resultBuffer>.results>.result>a.expand:before { content: "\\0020"; display: block; position: absolute; top: 8px; left: 6px; width: 8px; height: 2px; background: #fff; }
			.app-resultBuffer>.results>.result>a.expand:after { content: "\\0020"; display: block; position: absolute; top: 5px; left: 9px; width: 2px; height: 8px; background: #fff; }
			.app-resultBuffer>.results>.result>.num { float: left; margin-right: 2px; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }
			.app-resultBuffer>.results>.result>.value { float: left; margin-right: 2px; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }
			.app-resultBuffer>.results>.result>.type { float: left; margin-right: 1px; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }
			.app-resultBuffer>.results>.result>.details { clear: both; }

			.app-resultBuffer>.results>.result { border-color: #e0e0e0; background: #f3f3f3; }
			.app-resultBuffer>.results>.result>.num,
			.app-resultBuffer>.results>.result>.value,
			.app-resultBuffer>.results>.result>.type { background: #e0e0e0; color: #3b3b3b; }
			.app-resultBuffer>.results>.result>a.expand { background: #d9d9d9; color: #ffffff; }
			
			.app-resultBuffer>.results>.result.fail { border-color: #e2b5b5; background: #ffcccc; }
			.app-resultBuffer>.results>.result.fail>.num,
			.app-resultBuffer>.results>.result.fail>.value,
			.app-resultBuffer>.results>.result.fail>.type { background: #e2b5b5; color: #3d3232; }
			.app-resultBuffer>.results>.result.fail>a.expand { background: #db9a9a; color: #ffe3db; }
			
			.app-resultBuffer>.results>.result.success { border-color: #b5dfb5; background: #ccffcc; }
			.app-resultBuffer>.results>.result.success>.num,
			.app-resultBuffer>.results>.result.success>.value,
			.app-resultBuffer>.results>.result.success>.type { background: #b5dfb5; color: #3a473a; }
			.app-resultBuffer>.results>.result.success>a.expand { background: #85cc8c; color: #e4ffe0; }
			
			.app-resultBuffer>.results>.result.empty { border-color: #e0d9b6; background: #fff7cc; }
			.app-resultBuffer>.results>.result.empty>.num { background: #e0d9b6; color: #3b3930; }
			.app-resultBuffer>.results>.result.empty>.value,
			.app-resultBuffer>.results>.result.empty>.type { background: #e0d9b6; color: #3b3930; }
			.app-resultBuffer>.results>.result.empty>a.expand { background: #d9ce9a; color: #fdffdb; }
			
			.app-resultBuffer>.results>.result.expanded>a.expand:after { display: none; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @return string
	 */
	static public function getScripts() {
		return static::formatTextForOutput('<script type="text/javascript">/*<![CDATA[*/
			(function(){
				function toggleExpand(resultBufferNode) {
					if (spectrum.tools.hasClass(resultBufferNode, "expanded")) {
						spectrum.tools.removeClass(resultBufferNode, "expanded");
					} else {
						spectrum.tools.addClass(resultBufferNode, "expanded");
					} 
				}
				
				spectrum.tools.addEventListener(document, "DOMContentLoaded", function() {
					var resultBufferNodes = document.body.querySelectorAll(".app-resultBuffer>.results>.result");
					for (var i = 0; i < resultBufferNodes.length; i++) {
						spectrum.tools.addEventListener(resultBufferNodes[i], "click", function(e){
							e.preventDefault();
							
							// Uses middle click instead of double click for text selection by double click support
							if (e.button == 1) {
								toggleExpand(e.currentTarget);
							}
						});
	
						spectrum.tools.addEventListener(resultBufferNodes[i].querySelector("a.expand"), "click", function(e){
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
		$contentForResults = static::getContentForResults($spec->getResultBuffer()->getResults());
		
		if (trim($contentForResults) == '') {
			return null;
		}
		
		$content = '';
		$content .= '<div class="app-resultBuffer app-clearFix">';
		$content .= '<h1>' . static::translateAndEscapeHtml('Result buffer') . ':</h1>';
		$content .= $contentForResults;
		$content .= '</div>';
		return $content;
	}

	/**
	 * @return string
	 */
	static protected function getContentForResults(array $results) {
		$content = '';
		
		$num = 0;
		foreach ($results as $result) {
			$num++;
			
			if (!(($result['result'] === false && config::hasOutputResultBufferElements('all fail')) || ($result['result'] === true && config::hasOutputResultBufferElements('all success')) || ($result['result'] === null && config::hasOutputResultBufferElements('all empty')) || ($result['result'] !== false && $result['result'] !== true && $result['result'] !== null && config::hasOutputResultBufferElements('all unknown')))) {
				continue;
			}
			
			$content .= '<div class="result ' . static::getResultValueName($result['result']) . '">';
			$content .= '<a href="#" class="expand" title="' . static::translateAndEscapeHtml('Show/hide full details (also available by mouse middle click on the card)') . '">' . static::translateAndEscapeHtml('Expand/collapse') . '</a>';
			$content .= '<div class="num" title="' . static::translateAndEscapeHtml('Order') . '">' . static::translateAndEscapeHtml('No.') . ' ' . $num . '</div>';
			$content .= '<div class="value" title="' . static::translateAndEscapeHtml('Result') . '">' . static::escapeHtml(static::getResultValueName($result['result'])) . '</div>';
			$content .= '<div class="type" title="' . static::translateAndEscapeHtml('Type') . '">' . static::translateAndEscapeHtml(static::getType($result['details'])) . '</div>';
			$content .= '<div class="details">';
			$content .= static::getContentForResultDetails($result['details']);
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