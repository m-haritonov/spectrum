<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\resultBuffer;
use \spectrum\core\MatcherCallDetailsInterface;

class ResultBuffer extends \spectrum\core\plugins\basePlugins\reports\drivers\html\components\Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer { position: relative; margin: 0.5em 0 1em 0; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>h1 { float: left; margin-bottom: 2px; padding: 0.3em 0.5em 0 0; color: #888; font-size: 0.9em; font-weight: normal; }' . $this->getNewline() .

				$this->getIndention() . '.c-resultBuffer>.results { clear: both; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result { float: left; position: relative; margin: 0 2px 2px 0; border: 1px solid; border-left: 0; border-top: 0; border-radius: 4px; white-space: nowrap; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result>a.expand { float: left; position: relative; width: 19px; height: 1.6em; margin-right: 2px; border-radius: 4px 0 4px 0; font-size: 0.9em; font-weight: bold; text-decoration: none; text-align: center; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result>a.expand:before { content: "\\0020"; display: block; position: absolute; top: 8px; left: 6px; width: 8px; height: 2px; background: #fff; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result>a.expand:after { content: "\\0020"; display: block; position: absolute; top: 5px; left: 9px; width: 2px; height: 8px; background: #fff; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result>.num { float: left; margin-right: 2px; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result>.value { float: left; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result>.c-resultBuffer-details { clear: both; }' . $this->getNewline() .

				$this->getIndention() . '.c-resultBuffer>.results>.result.true { border-color: #b5dfb5; background: #ccffcc; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result.true>.num { background: #b5dfb5; color: #3a473a; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result.true>.value { background: #b5dfb5; color: #3a473a; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result.true>a.expand { background: #85cc8c; color: #e4ffe0; }' . $this->getNewline() .

				$this->getIndention() . '.c-resultBuffer>.results>.result.false { border-color: #e2b5b5; background: #ffcccc; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result.false>.num { background: #e2b5b5; color: #3d3232; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result.false>.value { background: #e2b5b5; color: #3d3232; }' . $this->getNewline() .
				$this->getIndention() . '.c-resultBuffer>.results>.result.false>a.expand { background: #db9a9a; color: #ffe3db; }' . $this->getNewline() .

				$this->getIndention() . '.c-resultBuffer>.results>.result.expand>a.expand:after { display: none; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getScripts()
	{
		return
			'<script type="text/javascript">
				spectrum.tools.addEventListener(document, "DOMContentLoaded", function()
				{
					function toggleExpand(resultBufferNode)
					{
						if (spectrum.tools.hasClass(resultBufferNode, "expand"))
							spectrum.tools.removeClass(resultBufferNode, "expand");
						else
							spectrum.tools.addClass(resultBufferNode, "expand");
					}
					
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
				});' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtml()
	{
		$output = '';

		$output .= '<div class="c-resultBuffer c-clearFix">' . $this->getNewline();
		$output .= $this->getIndention() . '<h1>' . $this->translate('Run results buffer contains') . ':</h1>' . $this->getNewline();
		$output .= $this->getIndention() . '<div class="results">' . $this->getNewline();
		$num = 0;
		foreach ($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->getResultBuffer()->getResults() as $result)
		{
			$num++;
			$output .= $this->getIndention(2) . '<div class="result ' . ($result['result'] ? 'true' : 'false') . '">' . $this->getNewline();
			$output .= $this->getIndention(3) . '<a href="#" class="expand" title="' . $this->translate('Show full details (also available by mouse middle click on the card)') . '"></a>' . $this->getNewline();
			$output .= $this->getIndention(3) . '<div class="num" title="' . $this->translate('Order in run results buffer') . '">' . $this->translate('No.') . ' ' . $num . '</div>' . $this->getNewline();
			$output .= $this->getIndention(3) . '<div class="value" title="' . $this->translate('Result, contains in run results buffer') . '">' . ($result['result'] ? 'true' : 'false') . '</div>' . $this->getNewline();
			$output .= $this->prependIndentionToEachLine($this->getHtmlForResultDetails($result['details']), 3) . $this->getNewline();
			$output .= $this->getIndention(2) . '</div>' . $this->getNewline();
		}

		$output .= $this->getIndention() . '</div>' . $this->getNewline();
		$output .= '</div>' . $this->getNewline();

		return $output;
	}

	protected function getHtmlForResultDetails($details)
	{
		if (is_object($details) && $details instanceof MatcherCallDetailsInterface)
			$component = $this->createComponent('resultBuffer\details\MatcherCall');
		else
			$component = $this->createComponent('resultBuffer\details\Unknown');

		return $component->getHtml($details);
	}
}