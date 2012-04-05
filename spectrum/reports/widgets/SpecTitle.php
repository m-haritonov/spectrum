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
			return $this->getAdditionalArgumentsDumpOut();
		else
			return $name;
	}

	protected function getAdditionalArgumentsDumpOut()
	{
		$output = '';
		foreach ($this->getOwnerPlugin()->getOwnerSpec()->getAdditionalArguments() as $arg)
			$output .= $arg . ', ';

		return mb_substr($output, 0, -2);
	}
}