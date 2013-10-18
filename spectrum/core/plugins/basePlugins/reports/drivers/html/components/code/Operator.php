<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\code;

class Operator extends \spectrum\core\plugins\basePlugins\reports\drivers\html\components\Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.c-code-operator { color: rgba(0, 0, 0, 0.6); }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml($operator)
	{

		return '<span class="c-code-operator ' . $this->getOperatorName($operator) . '">' . htmlspecialchars($operator) . '</span>';
	}

	protected function getOperatorName($operator)
	{
		if ($operator == '{' || $operator == '}')
			return 'curlyBrace';
		else
			return null;
	}
}