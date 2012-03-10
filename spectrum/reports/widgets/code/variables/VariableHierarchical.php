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

namespace net\mkharitonov\spectrum\reports\widgets\code\variables;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class VariableHierarchical extends Variable
{
	public function getStyles()
	{
		$widgetSelector = '.g-code-variables-' . htmlspecialchars($this->type);

		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . "$widgetSelector { display: inline-block; vertical-align: top; }" . $this->getNewline() .
				$this->getIndention() . "$widgetSelector $widgetSelector { display: inline; vertical-align: baseline; }" . $this->getNewline() .
				$this->getIndention() . "$widgetSelector>.elements:before { content: '\\2026'; }" . $this->getNewline() .
				$this->getIndention() . "$widgetSelector>.elements>.element { display: none; margin-left: 1px; padding-left: 20px; border-left: 1px solid rgba(150, 150, 150, 0.5); }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector { border-radius: 4px; background: rgba(200, 200, 200, 0.5); }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector $widgetSelector { padding: 0; border: 0; background: transparent; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector>.elements:before { display: none; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector>.elements>.element { display: block; }" . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	protected function getHtmlForElement($key, $val)
	{
		$keyHtml = '<span class="key">' . $this->codeWidget->getHtmlForOperator('[') . htmlspecialchars("$key") . $this->codeWidget->getHtmlForOperator(']') . '</span>';
		$operatorHtml = ' ' . $this->codeWidget->getHtmlForOperator('=>') . ' ';
		$valHtml = $this->trimNewline($this->codeWidget->getHtmlForVariable($val));

		return '<span class="element">' . $keyHtml . $operatorHtml . $valHtml . '</span>';
	}
}