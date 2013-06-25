<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets;

class SpecTitle extends \spectrum\core\specs\plugins\basePlugins\reports\drivers\html\widgets\Widget
{
	public function getHtml()
	{
		return
			'<span class="g-specTitle">' .
				'<span class="name">' . htmlspecialchars($this->getSpecName()) . '</span>' . $this->getNewline() .
				$this->getIndention() . '<span class="separator"> &mdash; </span>' . $this->getNewline() .
				$this->prependIndentionToEachLine($this->trimNewline($this->createWidget('totalResult\Result')->getHtml())) . $this->getNewline() .
			'</span>';
	}

	protected function getSpecName()
	{
		$spec = $this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec();
		$name = $this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->getName();
		$testFunctionArguments = $spec->getTestCallbackArgumentsThroughRunningAncestors();
		
		if ($name == '' && !$spec->getChildSpecs() && $testFunctionArguments)
		{
			$output = '';
			foreach ($testFunctionArguments as $argument)
				$output .= $argument . ', ';
	
			return mb_substr($output, 0, -2);
		}
		else
			return $name;
	}
}