<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components;

use \spectrum\core\details\MatcherCallInterface;
use spectrum\core\details\PhpErrorInterface;
use spectrum\core\details\UserFailInterface;
use spectrum\core\SpecInterface;

class resultBuffer extends \spectrum\core\plugins\reports\drivers\html\components\component
{
	static public function getStyles()
	{
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-resultBuffer { position: relative; margin: 0.5em 0 1em 0; }
			.c-resultBuffer>h1 { float: left; margin-bottom: 2px; padding: 0.3em 0.5em 0 0; color: #888; font-size: 0.9em; font-weight: normal; }

			.c-resultBuffer>.results { clear: both; }
			.c-resultBuffer>.results>.result { float: left; position: relative; margin: 0 2px 2px 0; border: 1px solid; border-left: 0; border-top: 0; border-radius: 4px; white-space: nowrap; }
			.c-resultBuffer>.results>.result>a.expand { float: left; position: relative; overflow: hidden; width: 19px; height: 0; padding-top: 1.6em; margin-right: 2px; border-radius: 4px 0 4px 0; font-size: 0.9em; font-weight: bold; text-decoration: none; text-align: center; }
			.c-resultBuffer>.results>.result>a.expand:before { content: "\\0020"; display: block; position: absolute; top: 8px; left: 6px; width: 8px; height: 2px; background: #fff; }
			.c-resultBuffer>.results>.result>a.expand:after { content: "\\0020"; display: block; position: absolute; top: 5px; left: 9px; width: 2px; height: 8px; background: #fff; }
			.c-resultBuffer>.results>.result>.num { float: left; margin-right: 2px; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }
			.c-resultBuffer>.results>.result>.value { float: left; margin-right: 2px; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }
			.c-resultBuffer>.results>.result>.failType { float: left; margin-right: 1px; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }
			.c-resultBuffer>.results>.result>.details { clear: both; }

			.c-resultBuffer>.results>.result { border-color: #e0e0e0; background: #f3f3f3; }
			.c-resultBuffer>.results>.result>.num,
			.c-resultBuffer>.results>.result>.value,
			.c-resultBuffer>.results>.result>.failType { background: #e0e0e0; color: #3b3b3b; }
			.c-resultBuffer>.results>.result>a.expand { background: #d9d9d9; color: #ffffff; }
			
			.c-resultBuffer>.results>.result.true { border-color: #b5dfb5; background: #ccffcc; }
			.c-resultBuffer>.results>.result.true>.num,
			.c-resultBuffer>.results>.result.true>.value,
			.c-resultBuffer>.results>.result.true>.failType { background: #b5dfb5; color: #3a473a; }
			.c-resultBuffer>.results>.result.true>a.expand { background: #85cc8c; color: #e4ffe0; }

			.c-resultBuffer>.results>.result.false { border-color: #e2b5b5; background: #ffcccc; }
			.c-resultBuffer>.results>.result.false>.num,
			.c-resultBuffer>.results>.result.false>.value,
			.c-resultBuffer>.results>.result.false>.failType { background: #e2b5b5; color: #3d3232; }
			.c-resultBuffer>.results>.result.false>a.expand { background: #db9a9a; color: #ffe3db; }
			
			.c-resultBuffer>.results>.result.null { border-color: #e0d9b6; background: #fff7cc; }
			.c-resultBuffer>.results>.result.null>.num { background: #e0d9b6; color: #3b3930; }
			.c-resultBuffer>.results>.result.null>.value,
			.c-resultBuffer>.results>.result.null>.failType { background: #e0d9b6; color: #3b3930; }
			.c-resultBuffer>.results>.result.null>a.expand { background: #d9ce9a; color: #fdffdb; }
			
			.c-resultBuffer>.results>.result.expanded>a.expand:after { display: none; }
		/*]]>*/</style>', 2);
	}

	static public function getScripts()
	{
		return static::formatTextForOutput('<script type="text/javascript">/*<![CDATA[*/
			(function(){
				function toggleExpand(resultBufferNode)
				{
					if (spectrum.tools.hasClass(resultBufferNode, "expanded"))
						spectrum.tools.removeClass(resultBufferNode, "expanded");
					else
						spectrum.tools.addClass(resultBufferNode, "expanded");
				}
				
				spectrum.tools.addEventListener(document, "DOMContentLoaded", function()
				{
					var resultBufferNodes = document.body.querySelectorAll(".c-resultBuffer>.results>.result");
					for (var i = 0; i < resultBufferNodes.length; i++)
					{
						spectrum.tools.addEventListener(resultBufferNodes[i], "click", function(e){
							e.preventDefault();
							
							// Uses middle click instead of double click for text selection by double click support
							if (e.button == 1)
								toggleExpand(e.currentTarget);
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

	static public function getHtml(SpecInterface $spec)
	{
		$results = $spec->getResultBuffer()->getResults();
		if (count($results) == 0)
			return null;
		
		$output = '';
		$output .= '<div class="c-resultBuffer c-clearFix">' . static::getHtmlEscapedOutputNewline();
		$output .= static::getHtmlEscapedOutputIndention() . '<h1>' . static::translateAndEscapeHtml('Result buffer') . ':</h1>' . static::getHtmlEscapedOutputNewline();
		$output .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getHtmlForResults($results)) . static::getHtmlEscapedOutputNewline();
		$output .= '</div>';
		return $output;
	}
	
	static protected function getHtmlForResults($results)
	{
		$output = '';
		$output .= '<div class="results">' . static::getHtmlEscapedOutputNewline();
		
		$num = 0;
		foreach ($results as $result)
		{
			$num++;
			$output .= static::getHtmlEscapedOutputIndention() . '<div class="result ' . static::getResultValueName($result['result']) . '">' . static::getHtmlEscapedOutputNewline();
			$output .= static::getHtmlEscapedOutputIndention(2) . '<a href="#" class="expand" title="' . static::translateAndEscapeHtml('Show full details (also available by mouse middle click on the card)') . '">' . static::translateAndEscapeHtml('Expand/collapse') . '</a>' . static::getHtmlEscapedOutputNewline();
			$output .= static::getHtmlEscapedOutputIndention(2) . '<div class="num" title="' . static::translateAndEscapeHtml('Order in run results buffer') . '">' . static::translateAndEscapeHtml('No.') . ' ' . $num . '</div>' . static::getHtmlEscapedOutputNewline();
			$output .= static::getHtmlEscapedOutputIndention(2) . '<div class="value" title="' . static::translateAndEscapeHtml('Result, contains in run results buffer') . '">' . static::escapeHtml(static::getResultValueName($result['result'])) . '</div>' . static::getHtmlEscapedOutputNewline();
			
			if ($result['result'] === false)
				$output .= static::getHtmlEscapedOutputIndention(2) . '<div class="failType" title="' . static::translateAndEscapeHtml('Fail type') . '">' . static::translateAndEscapeHtml(static::getFailType($result['details'])) . '</div>' . static::getHtmlEscapedOutputNewline();
			
			$output .= static::getHtmlEscapedOutputIndention(2) . '<div class="details">' . static::getHtmlEscapedOutputNewline();
			$output .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getHtmlForResultDetails($result['details']), 3) . static::getHtmlEscapedOutputNewline();
			$output .= static::getHtmlEscapedOutputIndention(2) . '</div>' . static::getHtmlEscapedOutputNewline();
			$output .= static::getHtmlEscapedOutputIndention() . '</div>' . static::getHtmlEscapedOutputNewline();
		}

		$output .= '</div>';
		return $output;
	}
	
	static protected function getResultValueName($result)
	{
		if ($result === false)
			return 'false';
		else if ($result === true)
			return 'true';
		else if ($result === null)
			return 'null';
		else
			return 'unknown';
	}
	
	static protected function getFailType($details)
	{
		if (is_object($details) && $details instanceof MatcherCallInterface)
			return 'matcher call fail';
		else if (is_object($details) && $details instanceof PhpErrorInterface)
			return 'php error';
		else if (is_object($details) && $details instanceof UserFailInterface)
			return 'user fail';
		else
			return 'unknown fail';
	}

	static protected function getHtmlForResultDetails($details)
	{
		if (is_object($details) && $details instanceof MatcherCallInterface)
			return static::callComponentMethod('details\matcherCall', 'getHtml', array($details));
		else if (is_object($details) && $details instanceof PhpErrorInterface)
			return static::callComponentMethod('details\phpError', 'getHtml', array($details));
		else if (is_object($details) && $details instanceof UserFailInterface)
			return static::callComponentMethod('details\userFail', 'getHtml', array($details));
		else
			return static::callComponentMethod('details\unknown', 'getHtml', array($details));
	}
}