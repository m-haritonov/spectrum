<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components;

class TotalInfo extends Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.c-totalInfo { margin: 1em 0; padding: 6px 10px; border-radius: 4px; background: #e5e5e5; }' . $this->getNewline() .
				$this->getIndention() . '.c-totalInfo>div { display: inline; }' . $this->getNewline() .
				$this->getIndention() . '.c-totalInfo h1 { display: inline; color: #333; font-size: 1em; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml()
	{
		if ($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->getParentSpecs())
			return;

		return
			'<div class="c-totalInfo">' . $this->getNewline() .
				'<div class="result">' . $this->getNewline() .
					'<h1>' . $this->translate('Total result') . ':</h1>' . $this->getNewline() .
					$this->prependIndentionToEachLine($this->createComponent('totalResult\Result')->getHtml()) . $this->getNewline() .
				'</div> | ' . $this->getNewline() .

				'<div class="details">' .
					'' . $this->translate('Details') . ': ' .
					$this->createComponent('DetailsControl')->getHtml() .
				'</div>' . $this->getNewline() .
			'</div>' . $this->getNewline();
	}

	public function getHtmlForUpdate()
	{
		if ($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->getParentSpecs())
			return;

		return '<div>' . $this->createComponent('totalResult\Update')->getHtml($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->getResultBuffer()->getTotalResult()) . '</div>';
	}
}