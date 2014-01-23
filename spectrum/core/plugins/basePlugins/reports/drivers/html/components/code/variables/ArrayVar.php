<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables;

class ArrayVar extends VariableHierarchical
{
	protected $type = 'array';

	public function getHtml($variable)
	{
		$output = '';
		$output .= '<span class="c-code-variables-' . htmlspecialchars($this->type) . ' c-code-variables">';
		$output .= $this->getHtmlForType($variable) . $this->getNewline();
		$output .= $this->createComponent('code\Operator')->getHtml('{');

		if (count($variable))
		{
			$output .= '<span class="elements">';
			foreach ($variable as $key => $val)
				$output .= $this->getHtmlForElement($key, $val);

			$output .= '</span>';
		}

		$output .= $this->createComponent('code\Operator')->getHtml('}');
		$output .= '</span>';

		return $output;
	}

	protected function getHtmlForType($variable)
	{
		return '<span class="type">' . htmlspecialchars($this->type) . '<span title="' . $this->translate('Elements count') . '">(' . count($variable) . ')</span></span>';
	}
}