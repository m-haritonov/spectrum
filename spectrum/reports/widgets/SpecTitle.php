<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets;
use \spectrum\core\SpecContainerDescribeInterface;
use \spectrum\core\SpecContainerContextInterface;
use \spectrum\core\SpecItemItInterface;
use \spectrum\core\SpecContainerInterface;
use \spectrum\core\SpecItemInterface;

class SpecTitle extends \spectrum\reports\widgets\Widget
{
	public function getHtml()
	{
		return
			'<span class="g-specTitle">' .
				'<span class="name">' . htmlspecialchars($this->getSpecName()) . '</span>' . $this->getNewline() .
				$this->getIndention() . '<span class="separator"> — </span>' . $this->getNewline() .
				$this->prependIndentionToEachLine($this->trimNewline($this->getOwnerPlugin()->createWidget('finalResult\Result')->getHtml())) . $this->getNewline() .
			'</span>';
	}

	protected function getSpecName()
	{
		$parent = $this->getOwnerPlugin()->getOwnerSpec()->getParent();
		$name = $this->getOwnerPlugin()->getOwnerSpec()->getName();

		if ($name == '' && $parent && $parent instanceof \spectrum\core\SpecContainerArgumentsProviderInterface)
			return $this->getTestCallbackArgumentsDumpOut();
		else
			return $name;
	}

	protected function getTestCallbackArgumentsDumpOut()
	{
		$output = '';
		foreach ($this->getOwnerPlugin()->getOwnerSpec()->getTestCallbackArguments() as $arg)
			$output .= $arg . ', ';

		return mb_substr($output, 0, -2);
	}
}