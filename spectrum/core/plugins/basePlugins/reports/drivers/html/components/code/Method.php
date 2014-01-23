<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\code;

class Method extends \spectrum\core\plugins\basePlugins\reports\drivers\html\components\Component
{
	public function getHtml($methodName, array $arguments)
	{
		return
			'<span class="c-code-method">' .
				'<span class="methodName">' . htmlspecialchars($methodName) . '</span>' .
				$this->createComponent('code\Operator')->getHtml('(') .
				'<span class="arguments">' . $this->getHtmlForArguments($arguments) . '</span>' .
			$this->createComponent('code\Operator')->getHtml(')') .
			'</span>';
	}

	public function getHtmlForArguments(array $arguments)
	{
		$output = '';
		foreach ($arguments as $argument)
			$output .= $this->createComponent('code\Variable')->getHtml($argument) . ', ';

		return mb_substr($output, 0, -2);
	}
}