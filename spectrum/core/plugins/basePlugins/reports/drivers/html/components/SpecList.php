<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
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
				$this->getIndention() . '.c-specList { padding-right: 35px; list-style: none; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li { margin-top: 3px; white-space: nowrap; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li .indention { display: inline-block; width: 0; white-space: pre; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.head { display: inline-block; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.head>.point { position: relative; padding: 1px 16px 1px 6px; border-radius: 20px; background: #e5e5e5; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.head>.point>.number { font-size: 0.9em; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.head>.point>.number .dot { display: inline-block; width: 0; color: transparent; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.head>.point>a.expand { display: block; position: absolute; top: 0; right: 0; bottom: 0; left: 0; padding-right: 2px; text-decoration: none; text-align: right; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.head>.point>a.expand span { display: inline-block; position: relative; width: 8px; height: 8px; border: 1px solid #bbb; background: #ccc; border-radius: 5px; vertical-align: middle; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.head>.point>a.expand span:before { content: "\\0020"; display: block; position: absolute; top: 3px; right: 1px; bottom: 3px; left: 1px; background: #fff; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.head>.point>a.expand span:after { content: "\\0020"; display: block; position: absolute; top: 1px; right: 3px; bottom: 1px; left: 3px; background: #fff; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li>.head>.title { display: inline-block; vertical-align: top; white-space: normal; }' . $this->getNewline() .
			
				$this->getIndention() . '.c-specList>li>.c-specList { padding-right: 0; }' . $this->getNewline() .
			
				$this->getIndention() . '.c-specList>li.notEnding>.c-specList { display: none; margin-left: 30px; white-space: normal; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.notEnding>.c-specList>li { position: relative; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.notEnding>.c-specList>li:before { content: "\\0020"; display: block; position: absolute; top: -3px; bottom: 0; left: -18px; width: 1px; background: #ccc; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.notEnding>.c-specList>li:after { content: "\\0020"; display: block; position: absolute; top: 8px; left: -17px; width: 17px; height: 1px; background: #ccc; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.notEnding>.c-specList>li:last-child:before { bottom: auto; height: 12px; }' . $this->getNewline() .
			
				$this->getIndention() . '.c-specList>li.ending>.body { display: inline-block; vertical-align: top; white-space: normal; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.ending>.body>.runDetails { display: none; }' . $this->getNewline() .
				
				$this->getIndention() . '.c-specList>li.noContent>.head>.point>a.expand { display: none; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.noContent>.head>.point { padding-right: 6px; }' . $this->getNewline() .

				$this->getIndention() . '.c-specList>li.expand.notEnding>.head { position: relative; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.expand.notEnding>.head:before { content: "\\0020"; display: block; position: absolute; top: 0; bottom: 0; left: 12px; width: 1px; background: #ccc; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.expand.notEnding>.c-specList { display: block; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.expand.ending>.body>.runDetails { display: block; }' . $this->getNewline() .
				$this->getIndention() . '.c-specList>li.expand>.head>.point>a.expand span:after { display: none; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getScripts()
	{
		return
			'<script type="text/javascript">
				spectrum.tools.addEventListener(document, "DOMContentLoaded", function()
				{
					var expandLinkNodes = document.body.querySelectorAll(".c-specList>li>.head>.point>a.expand");
					for (var i = 0; i < expandLinkNodes.length; i++)
					{
						var liNode = expandLinkNodes[i].parentNode.parentNode.parentNode;

						if (liNode.querySelector(".runDetails, .c-specList") == null)
							spectrum.tools.addClass(liNode, "noContent");

						spectrum.tools.addEventListener(expandLinkNodes[i], "click", function(e){
							e.preventDefault();
							var liNode = e.currentTarget.parentNode.parentNode.parentNode;

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
			$output .= $this->getIndention(static::$depth) . '<ol class="c-specList">' . $this->getNewline();
		}

		if (!$spec->isAnonymous() && $spec->getParentSpecs())
		{
			@static::$numbers[static::$depth]++;

			$output .= $this->getIndention(static::$depth * 2 + 1) . '<li class="' . ($spec->getChildSpecs() ? 'notEnding expand' : 'ending') . '" id="' . $spec->getSpecId() . '">' . $this->getNewline();
			
			if ($spec->getChildSpecs())
			{
				$output .= $this->getIndention(static::$depth * 2 + 2) . '<div class="head">' . $this->getNewline();
				$output .= $this->prependIndentionToEachLine($this->getHtmlForSpecPoint(), static::$depth * 2 + 3) . $this->getNewline();
				$output .= $this->prependIndentionToEachLine($this->getHtmlForSpecTitle(), static::$depth * 2 + 3) . $this->getNewline();
				$output .= $this->getIndention(static::$depth * 2 + 2) . '</div>' . $this->getNewline();
				$output .= $this->getIndention(static::$depth * 2 + 2) . '<ol class="c-specList">' . $this->getNewline();
				static::$depth++;
			}
			else
			{
				$output .= $this->getIndention(static::$depth * 2 + 2) . '<div class="head">' . $this->getNewline();
				$output .= $this->prependIndentionToEachLine($this->getHtmlForSpecPoint(), static::$depth * 2 + 3) . $this->getNewline();
				$output .= $this->getIndention(static::$depth * 2 + 2) . '</div>' . $this->getNewline();
				$output .= $this->getIndention(static::$depth * 2 + 2) . '<div class="body">' . $this->getNewline();
				$output .= $this->prependIndentionToEachLine($this->getHtmlForSpecTitle(), static::$depth * 2 + 3) . $this->getNewline();
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
				$output .= $this->getIndention(static::$depth * 2 + 2) . '</ol>' . $this->getNewline();
			}
			else
			{
				$output .= $this->prependIndentionToEachLine($this->getHtmlForRunDetails($totalResult), static::$depth * 2 + 3) . $this->getNewline();
				$output .= $this->getIndention(static::$depth * 2 + 2) . '</div>' . $this->getNewline();
			}
			
			$output .= $this->prependIndentionToEachLine($this->createComponent('totalResult\Update')->getHtml($totalResult), static::$depth * 2 + 2) . $this->getNewline();
			$output .= $this->getIndention(static::$depth * 2 + 1) . '</li>' . $this->getNewline();
		}

		if (!$spec->getParentSpecs())
			$output .= $this->getIndention(static::$depth) . '</ol>' . $this->getNewline();

		return $output;
	}

	protected function getHtmlForSpecPoint()
	{
		return
			'<span class="point">' .
				str_repeat('<span class="indention">' . $this->getIndention() . '</span>', static::$depth) . // Indention for copy to clipboard
				'<span class="number">' . htmlspecialchars(@static::$numbers[static::$depth]) . '<span class="dot">.</span></span>' .
				'<a href="#" class="expand" title="' . $this->translate('Expand/collapse child content') . '"><span></span></a>' .
			'</span> ';
	}
	
	protected function getHtmlForSpecTitle()
	{
		return
			'<span class="title">' . $this->getNewline() .
				$this->getIndention() . '<span class="name">' . htmlspecialchars($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->getName()) . '</span>' . $this->getNewline() .
				$this->getIndention() . '<span class="separator"> &mdash; </span>' . $this->getNewline() .
				$this->prependIndentionToEachLine($this->trimNewline($this->createComponent('totalResult\Result')->getHtml())) . $this->getNewline() .
			'</span>';
	}

	protected function getHtmlForRunDetails($totalResult)
	{
		$output = '';

		if ($totalResult === false)
			$output .= $this->prependIndentionToEachLine($this->createComponent('resultBuffer\ResultBuffer')->getHtml()) . $this->getNewline();

		$output .= $this->prependIndentionToEachLine($this->createComponent('Messages')->getHtml()) . $this->getNewline();

		if (trim($output) != '')
		{
			$output =
				'<div class="runDetails c-clearFix">' . $this->getNewline() . 
					$output . 
				'</div>' . $this->getNewline();
		}

		return $output;
	}
}