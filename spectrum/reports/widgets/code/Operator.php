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

namespace spectrum\reports\widgets\code;

class Operator extends \spectrum\reports\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-code-operator { color: rgba(0, 0, 0, 0.6); }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml($operator)
	{

		return '<span class="g-code-operator ' . $this->getOperatorName($operator) . '">' . htmlspecialchars($operator) . '</span>';
	}

	protected function getOperatorName($operator)
	{
		if ($operator == '{' || $operator == '}')
			return 'curlyBrace';
		else
			return null;
	}
}