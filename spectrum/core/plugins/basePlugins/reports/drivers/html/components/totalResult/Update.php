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
					spectrum = window.spectrum || {};
					spectrum.totalResult = {};
					spectrum.totalResult.update = function()
					{
						var totalResultNode = spectrum.tools.getExecuteScriptNode();
						while (totalResultNode.className != "c-totalResult-update")
							totalResultNode = totalResultNode.parentNode;

						var resultNodes = document.querySelectorAll(".c-totalResult-result[data-id=\'" + totalResultNode.getAttribute("data-id") + "\']");
						for (var i = 0; i < resultNodes.length; i++)
						{
							resultNodes[i].innerHTML = totalResultNode.getAttribute("data-resultTitle");
							resultNodes[i].className += " " + totalResultNode.getAttribute("data-resultCssClass");
						}
					};
				})();' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtml($totalResult)
	{
		$resultInfo = $this->getResultInfo($totalResult);
		
		// Uses tag attributes instead of JavaScript function arguments for potential parsing support
		return
			'<span class="c-totalResult-update"
				data-id="' . htmlspecialchars(spl_object_hash($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec())) . '"
				data-resultTitle="' . $this->translate($resultInfo['title']) . '"
				data-resultCssClass="' . htmlspecialchars($resultInfo['cssClass']) . '"
			>' . $this->getNewline() .
				$this->getIndention() . '<script type="text/javascript">spectrum.totalResult.update();</script>' . $this->getNewline() .
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