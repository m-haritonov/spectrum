<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables;

class StringVar extends Variable
{
	protected $type = 'string';

	public function getStyles()
	{
		$componentSelector = '.c-code-variables-' . htmlspecialchars($this->type);

		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . "$componentSelector .quote { color: rgba(0, 0, 0, 0.6); }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value { white-space: pre; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value .char { display: inline-block; overflow: hidden; position: relative; height: 12px; }" . $this->getNewline() .

				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value .char.space { width: 8px; height: 10px; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value .char.space:before { content: '\\0020'; position: absolute; bottom: 1px; left: 49%; width: 2px; height: 2px; background: #bbb; }" . $this->getNewline() .

				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value .char.tab { width: 15px; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value .char.tab:before { content: '\\21E5'; position: absolute; right: 0; left: 0; text-align: center; color: #aaa; }" . $this->getNewline() .

				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value .char.cr { width: 14px; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value .char.cr:before { content: '\\21A9'; position: absolute; bottom: -1px; right: 0; left: 0; text-align: center; color: #aaa; }" . $this->getNewline() .

				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value .char.lf { width: 10px; height: 11px; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value .char.lf:before { content: '\\2193'; position: absolute; bottom: 3px; right: 0; left: 0; text-align: center; color: #aaa; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value .char.lf:after { content: '\\0020'; position: absolute; bottom: 2px; right: 2px; left: 2px; border-bottom: 1px solid #bbb; }" . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	protected function getHtmlForType($variable)
	{
		return '<span class="type">' . htmlspecialchars($this->type) . '<span title="' . $this->translate('String length') . '">(' . mb_strlen($variable) . ')</span></span>';
	}

	protected function getHtmlForValue($variable)
	{
		return ' <span class="quote open">&quot;</span>' .
			'<span class="value">' . $this->makeSpacesVisible(htmlspecialchars($variable)) . '</span>' .
			'<span class="quote close">&quot;</span>';
	}

	protected function makeSpacesVisible($string)
	{
		$string = str_replace(" ", '<span class="char space" title="' . $this->translate('Whitespace') . '"> </span>', $string);
		$string = str_replace("\t", '<span class="char tab" title="' . $this->translate('Tab ("\t")') . '">' . "\t" . '</span>', $string);

		$cr = '<span class="char cr" title="' . $this->translate('Carriage return ("\r")') . '"></span>';
		$lf = '<span class="char lf" title="' . $this->translate('Line feed ("\n")') . '"></span>';

		$string = strtr($string, array(
			"\r\n" => $cr . $lf . "\r\n",
			"\r" => $cr . "\r",
			"\n" => $lf . "\n",
		));

		return $string;
	}
}