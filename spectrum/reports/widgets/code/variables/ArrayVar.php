<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets\code\variables;

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