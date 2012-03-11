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
class StringVar extends Variable
{
	protected $type = 'string';

	public function getStyles()
	{
		$widgetSelector = '.g-code-variables-' . htmlspecialchars($this->type);

		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector .value { white-space: pre; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector .value .char { display: inline-block; overflow: hidden; position: relative; width: 10px; height: 1.2em; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector .value .char.space { width: 8px; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector .value .char.space:before { content: '\\2022'; position: absolute; left: 0; right: 0; text-align: center; color: #ccc; font-size: 10px; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector .value .char.tab { width: 15px; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector .value .char.tab:before { content: '\\21E5'; position: absolute; left: 0; right: 0; text-align: center; color: #ccc; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector .value .char.cr:before { content: '\\21A4'; position: absolute; top: -10%; left: 0; right: 0; text-align: center; color: #ccc; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector .value .char.lf:before { content: '\\21A7'; position: absolute; left: 0; right: 0; text-align: center; color: #ccc; }" . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	protected function getHtmlForType($variable)
	{
		return '<span class="type">' . htmlspecialchars($this->type) . '<span title="String length">(' . mb_strlen($variable) . ')</span></span>';
	}

	protected function getHtmlForValue($variable)
	{
		return ' <span class="quote open">&quot;</span>' .
			'<span class="value">' . $this->makeSpacesVisible(htmlspecialchars($variable)) . '</span>' .
			'<span class="quote close">&quot;</span>';
	}

	protected function makeSpacesVisible($string)
	{
		$string = str_replace(" ", '<span class="char space" title="Whitespace"> </span>', $string);
		$string = str_replace("\t", '<span class="char tab" title="Tab">' . "\t" . '</span>', $string);

		$cr = '<span class="char cr" title="Carriage return (CR)"></span>';
		$lf = '<span class="char lf" title="Line feed (LF)"></span>';

		$string = strtr($string, array(
			"\r\n" => $cr . $lf . "\r\n",
			"\r" => $cr . "\r",
			"\n" => $lf . "\n",
		));

		return $string;
	}
}