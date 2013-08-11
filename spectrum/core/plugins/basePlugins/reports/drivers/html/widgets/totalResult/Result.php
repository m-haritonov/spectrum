<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\totalResult;

class Result extends \spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-totalResult-result { color: #aaa; font-weight: bold; }' . $this->getNewline() .
				$this->getIndention() . '.g-totalResult-result.fail { color: #a31010; }' . $this->getNewline() .
				$this->getIndention() . '.g-totalResult-result.success { color: #009900; }' . $this->getNewline() .
				$this->getIndention() . '.g-totalResult-result.empty { color: #cc9900; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml()
	{
		// See "Update" widget to understand update logic
		return
			'<span class="g-totalResult-result" data-specUid="' . htmlspecialchars($this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->getSpecId()) . '">' .
				$this->translate('wait') . '...' .
			'</span>';
	}
}