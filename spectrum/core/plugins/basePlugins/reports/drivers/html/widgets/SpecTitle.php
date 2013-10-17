<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\widgets;

class SpecTitle extends Widget
{
	public function getHtml()
	{
		return
			'<span class="g-specTitle">' .
				'<span class="name">' . htmlspecialchars($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->getName()) . '</span>' . $this->getNewline() .
				$this->getIndention() . '<span class="separator"> &mdash; </span>' . $this->getNewline() .
				$this->prependIndentionToEachLine($this->trimNewline($this->createWidget('totalResult\Result')->getHtml())) . $this->getNewline() .
			'</span>';
	}
}