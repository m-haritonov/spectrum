<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace spectrum\reports\widgets\code\variables;

abstract class VariableHierarchical extends Variable
{
	public function getStyles()
	{
		$widgetSelector = '.g-code-variables-' . htmlspecialchars($this->type);

		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . "$widgetSelector { display: inline-block; vertical-align: top; border-radius: 4px; background: rgba(255, 255, 255, 0.5); }" . $this->getNewline() .
				$this->getIndention() . "$widgetSelector>.g-code-operator.curlyBrace { display: none; }" . $this->getNewline() .
				$this->getIndention() . "$widgetSelector $widgetSelector { display: inline; vertical-align: baseline; background: transparent; }" . $this->getNewline() .
				$this->getIndention() . "$widgetSelector>.elements:before { content: '\\007B\\2026\\007D'; color: rgba(0, 0, 0, 0.6); }" . $this->getNewline() .
				$this->getIndention() . "$widgetSelector>.elements>.element { display: none; }" . $this->getNewline() .
				$this->getIndention() . "$widgetSelector>.elements>.element>.indention { display: inline-block; overflow: hidden; width: 25px; white-space: pre; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector>.g-code-operator.curlyBrace { display: inline; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector $widgetSelector>.g-code-operator.curlyBrace { display: none; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector>.elements:before { display: none; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector>.elements>.element { display: block; }" . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	protected function getHtmlForElement($key, $val)
	{
		return
			'<span class="element">' .
				str_repeat('<span class="indention">' . $this->getIndention() . '</span>', $this->depth + 1) .
				'<span class="key">' . $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('[') . htmlspecialchars("$key") . $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml(']') . '</span>' .
				' ' . $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('=>') . ' ' .
				$this->trimNewline($this->getOwnerPlugin()->createWidget('code\Variable')->getHtml($val, $this->depth + 1)) .
			'</span>';
	}
}