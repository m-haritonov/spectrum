<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports\widgets\finalResult;

class Result extends \spectrum\reports\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-finalResult-result { color: #aaa; font-weight: bold; }' . $this->getNewline() .
				$this->getIndention() . '.g-finalResult-result.fail { color: #a31010; }' . $this->getNewline() .
				$this->getIndention() . '.g-finalResult-result.success { color: #009900; }' . $this->getNewline() .
				$this->getIndention() . '.g-finalResult-result.empty { color: #cc9900; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml()
	{
		// See "Update" widget to understand update logic
		return
			'<span class="g-finalResult-result" data-specUid="' . htmlspecialchars($this->getOwnerPlugin()->getOwnerSpec()->identify->getSpecId()) . '">' .
				$this->translate('wait') . '...' .
			'</span>';
	}
}