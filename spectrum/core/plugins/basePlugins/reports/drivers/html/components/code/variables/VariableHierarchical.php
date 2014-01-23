<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables;

abstract class VariableHierarchical extends Variable
{
	public function getStyles()
	{
		$componentSelector = '.c-code-variables-' . htmlspecialchars($this->type);

		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . "$componentSelector { display: inline-block; vertical-align: top; border-radius: 4px; background: rgba(255, 255, 255, 0.5); }" . $this->getNewline() .
				$this->getIndention() . "$componentSelector>.c-code-operator.curlyBrace { display: none; }" . $this->getNewline() .
				$this->getIndention() . "$componentSelector $componentSelector { display: inline; vertical-align: baseline; background: transparent; }" . $this->getNewline() .
				$this->getIndention() . "$componentSelector>.elements:before { content: '\\007B\\2026\\007D'; color: rgba(0, 0, 0, 0.6); }" . $this->getNewline() .
				$this->getIndention() . "$componentSelector>.elements>.element { display: none; }" . $this->getNewline() .
				$this->getIndention() . "$componentSelector>.elements>.element>.indention { display: inline-block; overflow: hidden; width: 25px; white-space: pre; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector>.c-code-operator.curlyBrace { display: inline; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector $componentSelector>.c-code-operator.curlyBrace { display: none; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector>.elements:before { display: none; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector>.elements>.element { display: block; }" . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	protected function getHtmlForElement($key, $val)
	{
		return
			'<span class="element">' .
				str_repeat('<span class="indention">' . $this->getIndention() . '</span>', $this->depth + 1) .
				'<span class="key">' . $this->createComponent('code\Operator')->getHtml('[') . htmlspecialchars("$key") . $this->createComponent('code\Operator')->getHtml(']') . '</span>' .
				' ' . $this->createComponent('code\Operator')->getHtml('=>') . ' ' .
				$this->trimNewline($this->createComponent('code\Variable')->getHtml($val, $this->depth + 1)) .
			'</span>';
	}
}