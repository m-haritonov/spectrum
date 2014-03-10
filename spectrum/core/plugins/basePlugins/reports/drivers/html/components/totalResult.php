<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components;

use spectrum\core\SpecInterface;

class totalResult extends component
{
	static public function getStyles()
	{
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-totalResult-result { color: #aaa; font-weight: bold; }
			.c-totalResult-result.fail { color: #a31010; }
			.c-totalResult-result.success { color: #009900; }
			.c-totalResult-result.empty { color: #cc9900; }
		
			.c-totalResult-update { display: none; }
		/*]]>*/</style>', 2);
	}

	static public function getScripts()
	{
		return static::formatTextForOutput('<script type="text/javascript">/*<![CDATA[*/
			spectrum = window.spectrum || {};
			spectrum.totalResult = {
				update: function()
				{
					var totalResultNode = spectrum.tools.getExecutingScriptNode();
					while (!spectrum.tools.hasClass(totalResultNode, "c-totalResult-update"))
						totalResultNode = totalResultNode.parentNode;
		
					var result = totalResultNode.querySelectorAll(".result")[0].innerHTML;
					var title = totalResultNode.querySelectorAll(".title")[0].innerHTML;
					var resultNodes = document.querySelectorAll(".c-totalResult-result." + spectrum.tools.getClassesByPrefix(totalResultNode, "id-")[0]);
					for (var i = 0; i < resultNodes.length; i++)
					{
						resultNodes[i].className += " " + result;
						resultNodes[i].innerHTML = title;
					}
				}
			};
		/*]]>*/</script>', 2);
	}

	static public function getHtml(SpecInterface $spec)
	{
		return
			'<span class="c-totalResult-result id-' . static::escapeHtml($spec->getRunId()) . '">' .
				static::translateAndEscapeHtml('wait...') .
			'</span>';
	}
	
	static public function getHtmlForUpdate(SpecInterface $spec)
	{
		$resultName = static::getResultName($spec->getResultBuffer()->getTotalResult());
		return
			'<span class="c-totalResult-update id-' . static::escapeHtml($spec->getRunId()) . '">' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<span class="result">' . static::escapeHtml($resultName) . '</span>' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<span class="title">' . static::translateAndEscapeHtml($resultName) . '</span>' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<script type="text/javascript">/*<![CDATA[*/spectrum.totalResult.update();/*]]>*/</script>' . static::getHtmlEscapedOutputNewline() .
			'</span>';
	}

	static protected function getResultName($result)
	{
		if ($result === false)
			return 'fail';
		else if ($result === true)
			return 'success';
		else
			return 'empty';
	}
}