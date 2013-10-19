<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components;

class Messages extends Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.c-messages { position: relative; margin: 0.5em 0 1em 0; }' . $this->getNewline() .
				$this->getIndention() . '.c-messages>h1 { float: left; margin-bottom: 2px; padding: 0.3em 0.5em 0 0; color: #888; font-size: 0.9em; font-weight: normal; }' . $this->getNewline() .
				$this->getIndention() . '.c-messages>ul { clear: both; float: left; list-style: none; }' . $this->getNewline() .
				$this->getIndention() . '.c-messages>ul>li { margin-bottom: 2px; padding: 0.4em 7px; border-radius: 4px; background: #e5e5e5; }' . $this->getNewline() .
				$this->getIndention() . '.c-messages>ul>li>.number { color: #888; font-size: 0.9em; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml()
	{
		$messages = $this->getOwnerDriver()->getOwnerPlugin()->getOwnerSpec()->messages->getAll();

		if (!count($messages))
			return null;

		$output = '';

		$output .= '<div class="c-messages c-clearFix">' . $this->getNewline();
		$output .= $this->getIndention() . '<h1>' . $this->translate('Messages') . ':</h1>' . $this->getNewline();
		$output .= $this->getIndention() . '<ul>' . $this->getNewline();
		$num = 0;
		foreach ($messages as $message)
		{
			$num++;
			$output .= $this->getIndention(2) . '<li>' . $this->getHtmlForNumber($num) . htmlspecialchars($message) . '</li>' . $this->getNewline();
		}

		$output .= $this->getIndention() . '</ul>' . $this->getNewline();
		$output .= '</div>' . $this->getNewline();

		return $output;
	}

	protected function getHtmlForNumber($num)
	{
		return '<span class="number">' . htmlspecialchars($num) . '. </span>';
	}
}