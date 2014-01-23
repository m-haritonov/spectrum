<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables;

class ObjectVar extends VariableHierarchical
{
	protected $type = 'object';

	public function getStyles()
	{
		$componentSelector = '.c-code-variables-' . htmlspecialchars($this->type);

		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . "$componentSelector>.class { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; color: #000; white-space: nowrap; vertical-align: top; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector>.class { display: inline; overflow: visible; max-width: none; white-space: normal; vertical-align: baseline; }" . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml($variable)
	{
		$properties = $this->getProperties($variable);

		$output = '';
		$output .= '<span class="c-code-variables-' . htmlspecialchars($this->type) . ' c-code-variables">';
		$output .= $this->getHtmlForType($variable, $properties);
		$output .= $this->getHtmlForClass($variable, $properties);
		$output .= $this->createComponent('code\Operator')->getHtml('{');

		if (count($properties))
		{
			$output .= '<span class="elements">';
			foreach ($properties as $key => $val)
			{
				if ($variable instanceof \Exception && $key == 'trace')
					$val = '<removed from reports preview>';
				
				$output .= $this->getHtmlForElement($key, $val);
			}

			$output .= '</span>';
		}

		$output .= $this->createComponent('code\Operator')->getHtml('}');
		$output .= '</span>';

		return $output;
	}

	protected function getHtmlForType($variable, $properties = array())
	{
		return
			'<span class="type">' .
				htmlspecialchars($this->type) . '<span title="' . $this->translate('Properties count') . '">(' . count($properties) . ')</span> ' .
			'</span>';
	}

	protected function getHtmlForClass($variable, $properties = array())
	{
		return '<span class="class">' . htmlspecialchars(get_class($variable)) . '</span> ';
	}

	protected function getProperties($variable)
	{
		return array_merge(get_object_vars($variable), $this->getNotPublicAndStaticProperties($variable));
	}

	protected function getNotPublicAndStaticProperties($variable)
	{
		$reflection = new \ReflectionClass($variable);
		$properties = $reflection->getProperties(
			\ReflectionProperty::IS_PROTECTED |
			\ReflectionProperty::IS_PRIVATE |
			\ReflectionProperty::IS_STATIC
		);

		$result = array();
		foreach ($properties as $property)
		{
			$property->setAccessible(true);
			$result[$property->getName()] = $property->getValue($variable);
		}

		return $result;
	}
}