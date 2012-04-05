<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets;

class TotalInfo extends \spectrum\reports\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-totalInfo { margin: 1em 0; padding: 6px 10px; border-radius: 4px; background: #ddd; }' . $this->getNewline() .
				$this->getIndention() . '.g-totalInfo>div { display: inline; }' . $this->getNewline() .
				$this->getIndention() . '.g-totalInfo h1 { display: inline; color: #333; font-size: 1em; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml()
	{
		if ($this->getOwnerPlugin()->getOwnerSpec()->getParent())
			return;

		return
			'<div class="g-totalInfo">' . $this->getNewline() .
				'<div class="result">' . $this->getNewline() .
					'<h1>' . $this->translate('Total result') . ':</h1>' . $this->getNewline() .
					$this->prependIndentionToEachLine($this->getOwnerPlugin()->createWidget('finalResult\Result')->getHtml()) . $this->getNewline() .
				'</div> | ' . $this->getNewline() .

				'<div class="details">' .
					'' . $this->translate('Details') . ': ' .
					$this->getOwnerPlugin()->createWidget('DetailsControl')->getHtml() .
				'</div>' . $this->getNewline() .

//				$this->prependIndentionToEachLine($this->getOwnerPlugin()->createWidget('Messages')->getHtml()) . $this->getNewline() .
			'</div>' . $this->getNewline();
	}

	public function getHtmlForUpdate($finalResult)
	{
		if ($this->getOwnerPlugin()->getOwnerSpec()->getParent())
			return;

		return '<div>' . $this->getOwnerPlugin()->createWidget('finalResult\Update')->getHtml($finalResult) . '</div>';
	}
}