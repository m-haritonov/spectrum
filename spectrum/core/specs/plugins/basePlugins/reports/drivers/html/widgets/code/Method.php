<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets\code;

class Method extends \spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets\Widget
{
	public function getHtml($methodName, array $arguments)
	{
		return
			'<span class="g-code-method">' .
				'<span class="methodName">' . htmlspecialchars($methodName) . '</span>' .
				$this->createWidget('code\Operator')->getHtml('(') .
				'<span class="arguments">' . $this->getHtmlForArguments($arguments) . '</span>' .
			$this->createWidget('code\Operator')->getHtml(')') .
			'</span>';
	}

	public function getHtmlForArguments(array $arguments)
	{
		$output = '';
		foreach ($arguments as $argument)
			$output .= $this->createWidget('code\Variable')->getHtml($argument) . ', ';

		return mb_substr($output, 0, -2);
	}
}