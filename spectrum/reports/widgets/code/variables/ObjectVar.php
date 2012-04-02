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
class ObjectVar extends VariableHierarchical
{
	protected $type = 'object';

	public function getStyles()
	{
		$widgetSelector = '.g-code-variables-' . htmlspecialchars($this->type);

		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . "$widgetSelector>.class { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; color: #000; white-space: nowrap; vertical-align: top; }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector>.class { display: inline; overflow: visible; max-width: auto; white-space: normal; vertical-align: baseline; }" . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml($variable)
	{
		$properties = $this->getProperties($variable);

		$output = '';
		$output .= '<span class="g-code-variables-' . htmlspecialchars($this->type) . ' g-code-variables">';
		$output .= $this->getHtmlForType($variable, $properties);
		$output .= $this->getHtmlForClass($variable, $properties);
		$output .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('{');

		if (count($properties))
		{
			$output .= '<span class="elements">';
			foreach ($properties as $key => $val)
				$output .= $this->getHtmlForElement($key, $val);

			$output .= '</span>';
		}

		$output .= $this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('}');
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