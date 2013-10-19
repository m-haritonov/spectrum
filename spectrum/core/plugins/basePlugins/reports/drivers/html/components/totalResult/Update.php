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
							var resultName = totalResultNode.getAttribute("data-resultName");
							resultNodes[i].innerHTML = resultName;
							resultNodes[i].className += " " + resultName;
						}
					};
				})();' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtml($totalResult)
	{
		$resultName = $this->getResultName($totalResult);
		
		// Uses tag attributes instead of JavaScript function arguments for potential parsing support
		return
			'<span class="c-totalResult-update"' .
				' data-id="' . htmlspecialchars(spl_object_hash($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec())) . '"' .
				' data-resultName="' . htmlspecialchars($resultName) . '"' .
			'>' . $this->getNewline() .
				$this->getIndention() . '<script type="text/javascript">spectrum.totalResult.update();</script>' . $this->getNewline() .
			'</span>';
	}

	protected function getResultName($result)
	{
		if ($result === false)
			return 'fail';
		else if ($result === true)
			return 'success';
		else
			return 'empty';
	}
}