<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets\code\variables;

class FunctionVar extends Variable
{
	protected $type = 'function';

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
		return ' <span class="value">' . get_class($variable) . '</span>';
	}
}