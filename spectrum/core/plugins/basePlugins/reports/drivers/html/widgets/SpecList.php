<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\widgets;

class SpecList extends Widget
{
	static protected $depth;
	static protected $numbers = array();

	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-specList { list-style: none; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li { margin-top: 3px; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.indention { display: inline-block; width: 0; white-space: pre; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.point { position: relative; padding: 1px 16px 1px 6px; border-radius: 20px; background: #e5e5e5; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.point>.number { font-size: 0.9em; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.point>.number .dot { display: inline-block; width: 0; color: transparent; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.point>a.expand { display: block; position: absolute; top: 0; right: 0; bottom: 0; left: 0; padding-right: 2px; text-decoration: none; text-align: right; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.point>a.expand span { display: inline-block; position: relative; width: 8px; height: 8px; border: 1px solid #bbb; background: #ccc; border-radius: 5px; vertical-align: middle; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.point>a.expand span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.point>a.expand span:after { content: "\\0020"; display: block; position: absolute; top: 1px; right: 3px; bottom: 1px; left: 3px; background: #fff; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.runDetails { display: none; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.g-specList { display: none; margin-left: 25px; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.g-specList>li { position: relative; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.g-specList>li:before { content: "\\0020"; display: block; position: absolute; top: -3px; bottom: 0; left: -18px; width: 1px; background: #ccc;  }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.g-specList>li:after { content: "\\0020"; display: block; position: absolute; top: 8px; left: -17px; width: 17px; height: 1px; background: #ccc; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.g-specList>li:last-child:before { bottom: auto; height: 12px; }' . $this->getNewline() .

				$this->getIndention() . '.g-specList>li.expand>.runDetails { display: block; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li.expand>.g-specList { display: block; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li.expand>.point>a.expand span:after { display: none; }' . $this->getNewline() .

				$this->getIndention() . '.g-specList>li.noChildContent>.point>a.expand { display: none; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li.noChildContent>.point { padding-right: 6px; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getScripts()
	{
		return
			'<script type="text/javascript">
				document.addEventListener("DOMContentLoaded", function()
				{
					var resultNodes = document.body.querySelectorAll(".g-specList>li>.point>a.expand");

					for (var i = 0; i < resultNodes.length; i++)
					{
						var anchorNode = resultNodes[i];
						var liNode = anchorNode.parentNode.parentNode;

						if (liNode.querySelector(".runDetails, .g-specList") == null)
							tools.addClass(liNode, "noChildContent");

						anchorNode.addEventListener("click", function(e){
							e.preventDefault();
							var liNode = e.currentTarget.parentNode.parentNode;

							if (tools.hasClass(liNode, "expand"))
								tools.removeClass(liNode, "expand");
							else
								tools.addClass(liNode, "expand");
						});
					}
				});' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtmlBegin()
	{
		$output = '';
		$spec = $this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec();
		
		if (!$spec->getParentSpecs())
		{
			static::$depth = 0;
			$output .= $this->getIndention($this->getSpecDepth() + 1) . '<ol class="g-specList">' . $this->getNewline();
		}

		if (!$spec->isAnonymous())
		{
			@static::$numbers[static::$depth]++;

			$output .= $this->getIndention($this->getSpecDepth() * 2 + 2) . '<li class="' . $this->getSpecCssClass() . ' expand" id="' . $spec->getSpecId() . '">' . $this->getNewline();
			$output .= $this->getHtmlForCurrentSpecIndention() . $this->getHtmlForSpecPoint() . $this->getNewline();
			$output .= $this->prependIndentionToEachLine($this->createWidget('SpecTitle')->getHtml(), $this->getSpecDepth() * 2 + 3) . $this->getNewline();

			if ($spec->getChildSpecs())
			{
				$output .= $this->getIndention($this->getSpecDepth() * 2 + 3) . '<ol class="g-specList">' . $this->getNewline();
				static::$depth++;
			}
		}

		return $output;
	}

	public function getHtmlEnd()
	{
		$output = '';
		$spec = $this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec();
		$totalResult = $spec->getResultBuffer()->getTotalResult();

		if (!$spec->isAnonymous())
		{
			if ($spec->getChildSpecs())
			{
				static::$numbers[static::$depth] = 0;
				static::$depth--;
				$output .= $this->getIndention($this->getSpecDepth() * 2 + 3) . '</ol>' . $this->getNewline();
			}

			$output .= $this->prependIndentionToEachLine($this->createWidget('totalResult\Update')->getHtml($totalResult), $this->getSpecDepth() * 2 + 3) . $this->getNewline();
			$output .= $this->getIndention($this->getSpecDepth() * 2 + 3) . $this->trimNewline($this->getRunDetails($totalResult)) . $this->getNewline();
			$output .= $this->getIndention($this->getSpecDepth() * 2 + 2) . '</li>' . $this->getNewline();
		}

		if (!$spec->getParentSpecs())
			$output .= $this->getIndention($this->getSpecDepth() + 1) . '</ol>' . $this->getNewline();

		return $output;
	}

	protected function getSpecDepth()
	{
		return static::$depth;
	}

	protected function getSpecNumber()
	{
		return @static::$numbers[static::$depth];
	}

	protected function getHtmlForSpecPoint()
	{
		return
			'<span class="point">' .
				'<span class="number">' . htmlspecialchars($this->getSpecNumber()) . '<span class="dot">.</span></span>' .
				'<a href="#" class="expand" title="' . $this->translate('Expand/collapse child content') . '"><span></span></a>' .
			'</span> ';
	}

	protected function getHtmlForCurrentSpecIndention()
	{
		return $this->getIndention($this->getSpecDepth() * 2 + 3) . str_repeat('<span class="indention">' . $this->getIndention() . '</span>', $this->getSpecDepth());
	}

	protected function getRunDetails($totalResult)
	{
		$output = '';

		if ($totalResult === false)
			$output .= $this->createWidget('resultBuffer\ResultBuffer')->getHtml() . $this->getNewline();

		$output .= $this->prependIndentionToEachLine($this->createWidget('Messages')->getHtml(), $this->getSpecDepth() * 2 + 3) . $this->getNewline();

		if (trim($output) != '')
			$output = '<div class="runDetails g-clearFix">' . $output . '</div>';

		return $output;
	}

	protected function getSpecCssClass()
	{
		if ($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->getChildSpecs())
			return 'notEnding';
		else
			return 'ending';
	}
}