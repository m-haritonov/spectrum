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
			'</style>' . $this->getNewline();
	}

	protected function getHtmlForType($variable)
	{
		return '<span class="type">' . htmlspecialchars($this->type) . '<span title="String length">(' . mb_strlen($variable) . ')</span></span>';
	}

	protected function getHtmlForValue($variable)
	{
		return ' <span class="quote open">&quot;</span>' .
			'<span class="value">' . htmlspecialchars($variable) . '</span>' .
			'<span class="quote close">&quot;</span>';
	}
}