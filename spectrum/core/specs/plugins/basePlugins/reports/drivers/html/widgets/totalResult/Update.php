<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets\totalResult;

class Update extends \spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-totalResult-update { display: none; }' . $this->getNewline() .
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
						var resultNodes = document.querySelectorAll(".g-totalResult-result[data-specUid=\'" + totalResult.specUid + "\']");
						for (var i = 0; i < resultNodes.length; i++)
						{
							var resultNode = resultNodes[i];
							resultNode.className += " " + totalResult.resultAlias;
							resultNode.innerHTML = totalResult.resultTitle;
						}
					}

					function getTotalResult()
					{
						var totalResultNode = getExecutedScriptNode();
						while (totalResultNode.className != "g-totalResult-update")
							totalResultNode = totalResultNode.parentNode;

						return {
							specUid: totalResultNode.getAttribute("data-specUid"),
							resultAlias: totalResultNode.getAttribute("data-resultAlias"),
							resultTitle: totalResultNode.getAttribute("data-resultTitle")
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
		$resultAlias = $this->getTotalResultAlias($totalResult);
		return
			'<span class="g-totalResult-update"
				data-specUid="' . htmlspecialchars($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->getSpecId()) . '"
				data-resultAlias="' . htmlspecialchars($resultAlias) . '"
				data-resultTitle="' . $this->translate($resultAlias) . '">' . $this->getNewline() .

				$this->getIndention() . '<script type="text/javascript">totalResult.update();</script>' . $this->getNewline() .
			'</span>';
	}

	protected function getTotalResultAlias($result)
	{
		if ($result === false)
			$alias = 'fail';
		else if ($result === true)
			$alias = 'success';
		else
			$alias = 'empty';

		return $alias;
	}
}