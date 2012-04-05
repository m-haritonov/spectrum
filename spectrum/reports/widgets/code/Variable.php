<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets\code;

class Variable extends \spectrum\reports\widgets\Widget
{
	public function getHtml($variable, $depth = 0)
	{
		$className = $this->getVariableType($variable) . 'Var';
		$className = mb_strtoupper(mb_substr($className, 0, 1)) . mb_substr($className, 1);
		return $this->getOwnerPlugin()->createWidget('code\variables\\' . $className, $depth)->getHtml($variable);
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