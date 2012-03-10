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

namespace net\mkharitonov\spectrum\reports\widgets\code;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Code extends \net\mkharitonov\spectrum\reports\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-code-operator { color: rgba(0, 0, 0, 0.6); }' . $this->getNewline() .
				$this->getIndention() . '.g-code-method>.methodName {  }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtmlForOperator($operator)
	{
		return '<span class="g-code-operator">' . htmlspecialchars($operator) . '</span>';
	}

	public function getHtmlForPropertyAccess($propertyName)
	{
		return '<span class="g-code-property">' . htmlspecialchars($propertyName) . '</span>';
	}

	public function getHtmlForMethod($methodName, array $arguments)
	{
		return
			'<span class="g-code-method">' .
				'<span class="methodName">' . htmlspecialchars($methodName) . '</span>' .
				$this->getHtmlForOperator('(') .
				'<span class="arguments">' . $this->getHtmlForArguments($arguments) . '</span>' .
				$this->getHtmlForOperator(')') .
			'</span>';
	}

	public function getHtmlForArguments(array $arguments)
	{
		$output = '';
		foreach ($arguments as $argument)
			$output .= $this->getHtmlForVariable($argument) . ', ';

		return mb_substr($output, 0, -2);
	}

	public function getHtmlForVariable($variable)
	{
		$type = $this->getVariableType($variable);
		$variableWidgetClassName = mb_strtoupper(mb_substr($type, 0, 1)) . mb_substr($type, 1) . 'Var';
		$variableWidgetClass = '\net\mkharitonov\spectrum\reports\widgets\code\variables\\' . $variableWidgetClassName;
		$variableWidget = new $variableWidgetClass($this->getOwnerPlugin());
		return $variableWidget->getHtml($variable);
	}

	public function getVariableType($variable)
	{
		$type = mb_strtolower(gettype($variable));

		if ($type == 'boolean')
			$type = 'bool';
		else if ($type == 'integer')
			$type = 'int';
		else if ($type == 'double')
			$type = 'float';
		else if ($type == 'string')
			$type = 'string';
		else if ($type == 'array')
			$type = 'array';
		else if ($type == 'object')
		{
			$closure = function(){};
			if ($variable instanceof $closure)
				$type = 'closure';
			else
				$type = 'object';
		}
		else if ($type == 'resource')
			$type = 'resource';
		else if ($type == 'null')
			$type = 'null';
		else
			$type = 'unknown';

		return $type;
	}
}