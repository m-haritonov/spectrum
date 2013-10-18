<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\totalResult;

class Update extends \spectrum\core\plugins\basePlugins\reports\drivers\html\components\Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.c-totalResult-update { display: none; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getScripts()
	{
		return
			'<script type="text/javascript">
				(function(){
					totalResult = window.totalResult || {};
					totalResult.update = function()
					{
						var totalResult = getTotalResult();
						var resultNodes = document.querySelectorAll(".c-totalResult-result[data-specId=\'" + totalResult.specId + "\']");
						for (var i = 0; i < resultNodes.length; i++)
						{
							resultNodes[i].innerHTML = totalResult.resultTitle;
							resultNodes[i].className += " " + totalResult.resultCssClass;
						}
					}

					function getTotalResult()
					{
						var totalResultNode = getExecutedScriptNode();
						while (totalResultNode.className != "c-totalResult-update")
							totalResultNode = totalResultNode.parentNode;

						return {
							specId: totalResultNode.getAttribute("data-specId"),
							resultTitle: totalResultNode.getAttribute("data-resultTitle"),
							resultCssClass: totalResultNode.getAttribute("data-resultCssClass")
						};
					}

					function getExecutedScriptNode()
					{
						var scripts = document.getElementsByTagName("script");
						return scripts[scripts.length - 1];
					}
				})();' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtml($totalResult)
	{
		$resultInfo = $this->getResultInfo($totalResult);
		return
			'<span class="c-totalResult-update"
				data-specId="' . htmlspecialchars($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->getSpecId()) . '"
				data-resultTitle="' . $this->translate($resultInfo['title']) . '"
				data-resultCssClass="' . htmlspecialchars($resultInfo['cssClass']) . '"
			>' . $this->getNewline() .
				$this->getIndention() . '<script type="text/javascript">totalResult.update();</script>' . $this->getNewline() .
			'</span>';
	}

	protected function getResultInfo($result)
	{
		if ($result === false)
			return array('title' => 'fail', 'cssClass' => 'fail');
		else if ($result === true)
			return array('title' => 'success', 'cssClass' => 'success');
		else
			return array('title' => 'empty', 'cssClass' => 'empty');
	}
}