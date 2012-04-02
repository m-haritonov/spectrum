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
class ArrayVar extends VariableHierarchical
{
	protected $type = 'array';

	public function getHtml($variable)
	{
		$output = '';
		$output .= '<span class="g-code-variables-' . htmlspecialchars($this->type) . ' g-code-variables">';
		$output .= $this->getHtmlForType($variable) . $this->getNewline();
		$output .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('{');

		if (count($variable))
		{
			$output .= '<span class="elements">';
			foreach ($variable as $key => $val)
				$output .= $this->getHtmlForElement($key, $val);

			$output .= '</span>';
		}

		$output .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('}');
		$output .= '</span>';

		return $output;
	}

	protected function getHtmlForType($variable)
	{
		return '<span class="type">' . htmlspecialchars($this->type) . '<span title="' . $this->translate('Elements count') . '">(' . count($variable) . ')</span></span>';
	}
}