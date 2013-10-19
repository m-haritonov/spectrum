<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components;

class SpecList extends Component
{
	static protected $depth;
	static protected $numbers = array();

	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.c-specList { list-style: none; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li { margin-top: 3px; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.indention { display: inline-block; width: 0; white-space: pre; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.point { position: relative; padding: 1px 16px 1px 6px; border-radius: 20px; background: #e5e5e5; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.point>.number { font-size: 0.9em; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.point>.number .dot { display: inline-block; width: 0; color: transparent; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.point>a.expand { display: block; position: absolute; top: 0; right: 0; bottom: 0; left: 0; padding-right: 2px; text-decoration: none; text-align: right; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.point>a.expand span { display: inline-block; position: relative; width: 8px; height: 8px; border: 1px solid #bbb; background: #ccc; border-radius: 5px; vertical-align: middle; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.point>a.expand span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.point>a.expand span:after { content: "\\0020"; display: block; position: absolute; top: 1px; right: 3px; bottom: 1px; left: 3px; background: #fff; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.runDetails { display: none; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.c-specList { display: none; margin-left: 25px; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.c-specList>li { position: relative; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.c-specList>li:before { content: "\\0020"; display: block; position: absolute; top: -3px; bottom: 0; left: -18px; width: 1px; background: #ccc;  }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.c-specList>li:after { content: "\\0020"; display: block; position: absolute; top: 8px; left: -17px; width: 17px; height: 1px; background: #ccc; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.c-specList>li:last-child:before { bottom: auto; height: 12px; }' . $this->getNewline() .

				$this->getIndention() . '.c-specList>li.expand>.runDetails { display: block; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.expand>.c-specList { display: block; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.expand>.point>a.expand span:after { display: none; }' . $this->getNewline() .

				$this->getIndention() . '.c-specList>li.noChildContent>.point>a.expand { display: none; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.noChildContent>.point { padding-right: 6px; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getScripts()
	{
		return
			'<script type="text/javascript">
				spectrum.tools.addEventListener(document, "DOMContentLoaded", function()
				{
					var expandLinkNodes = document.body.querySelectorAll(".c-specList>li>.point>a.expand");
					for (var i = 0; i < expandLinkNodes.length; i++)
					{
						var liNode = expandLinkNodes[i].parentNode.parentNode;

						if (liNode.querySelector(".runDetails, .c-specList") == null)
							spectrum.tools.addClass(liNode, "noChildContent");

						spectrum.tools.addEventListener(expandLinkNodes[i], "click", function(e){
							e.preventDefault();
							var liNode = e.currentTarget.parentNode.parentNode;

							if (spectrum.tools.hasClass(liNode, "expand"))
								spectrum.tools.removeClass(liNode, "expand");
							else
								spectrum.tools.addClass(liNode, "expand");
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
			$output .= $this->getIndention($this->getSpecDepth() + 1) . '<ol class="c-specList">' . $this->getNewline();
		}

		if (!$spec->isAnonymous() && $spec->getParentSpecs())
		{
			@static::$numbers[static::$depth]++;

			$output .= $this->getIndention($this->getSpecDepth() * 2 + 2) . '<li class="' . $this->getSpecCssClass() . ' expand" id="' . $spec->getSpecId() . '">' . $this->getNewline();
			$output .= $this->getHtmlForCurrentSpecIndention() . $this->getHtmlForSpecPoint() . $this->getNewline();
			$output .= $this->prependIndentionToEachLine($this->createComponent('SpecTitle')->getHtml(), $this->getSpecDepth() * 2 + 3) . $this->getNewline();

			if ($spec->getChildSpecs())
			{
				$output .= $this->getIndention($this->getSpecDepth() * 2 + 3) . '<ol class="c-specList">' . $this->getNewline();
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

		if (!$spec->isAnonymous() && $spec->getParentSpecs())
		{
			if ($spec->getChildSpecs())
			{
				static::$numbers[static::$depth] = 0;
				static::$depth--;
				$output .= $this->getIndention($this->getSpecDepth() * 2 + 3) . '</ol>' . $this->getNewline();
			}

			$output .= $this->prependIndentionToEachLine($this->createComponent('totalResult\Update')->getHtml($totalResult), $this->getSpecDepth() * 2 + 3) . $this->getNewline();
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
			$output .= $this->createComponent('resultBuffer\ResultBuffer')->getHtml() . $this->getNewline();

		$output .= $this->prependIndentionToEachLine($this->createComponent('Messages')->getHtml(), $this->getSpecDepth() * 2 + 3) . $this->getNewline();

		if (trim($output) != '')
			$output = '<div class="runDetails c-clearFix">' . $output . '</div>';

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