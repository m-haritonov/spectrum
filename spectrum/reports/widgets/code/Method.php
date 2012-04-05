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

namespace spectrum\reports\widgets\code;

class Method extends \spectrum\reports\widgets\Widget
{
	public function getHtml($methodName, array $arguments)
	{
		return
			'<span class="g-code-method">' .
				'<span class="methodName">' . htmlspecialchars($methodName) . '</span>' .
				$this->getOwnerPlugin()->createWidget('code\Operator')->getHtml('(') .
				'<span class="arguments">' . $this->getHtmlForArguments($arguments) . '</span>' .
			$this->getOwnerPlugin()->createWidget('code\Operator')->getHtml(')') .
			'</span>';
	}

	public function getHtmlForArguments(array $arguments)
	{
		$output = '';
		foreach ($arguments as $argument)
			$output .= $this->getOwnerPlugin()->createWidget('code\Variable')->getHtml($argument) . ', ';

		return mb_substr($output, 0, -2);
	}
}