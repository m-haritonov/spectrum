<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets\code\variables;

class ClosureVar extends Variable
{
	protected $type = 'closure';

	public function getStyles()
	{
		$widgetSelector = '.g-code-variables-' . htmlspecialchars($this->type);

		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector .value { white-space: pre; }" . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	protected function getHtmlForValue($variable)
	{
		return ' <span class="value">' . htmlspecialchars($this->getClosureCode($variable)) . '</span>';
	}

	protected function getClosureCode($variable)
	{
		$reflection = new \ReflectionFunction($variable);
		$args = array();
		foreach ($reflection->getParameters() as $param)
			$args[] = '$' . $param->getName();

		$file = new \SplFileObject($reflection->getFileName());
		$file->seek($reflection->getStartLine() - 1);
		$code = '';
		while ($file->key() < $reflection->getEndLine())
		{
			$code .= $file->current();
			$file->next();
		}

		$start = mb_strpos($code, '{');
		$end = mb_strrpos($code, '}');

		return 'function(' . implode(', ', $args) . ') ' . mb_substr($code, $start, $end - $start);
	}
}