<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables;

class FunctionVar extends Variable
{
	protected $type = 'function';

	public function getStyles()
	{
		$componentSelector = '.c-code-variables-' . htmlspecialchars($this->type);

		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value { white-space: pre; }" . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	protected function getHtmlForValue($variable)
	{
		return ' <span class="value">' . get_class($variable) . '</span>';
	}
}