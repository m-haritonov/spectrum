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

class Messages extends \spectrum\reports\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-messages { position: relative; margin: 0.5em 0 1em 0; }' . $this->getNewline() .
				$this->getIndention() . '.g-messages>h1 { float: left; margin-bottom: 2px; padding: 0.3em 0.5em 0 7px; color: #888; font-size: 0.9em; font-weight: normal; }' . $this->getNewline() .
				$this->getIndention() . '.g-messages>ul { clear: both; float: left; list-style: none; }' . $this->getNewline() .
				$this->getIndention() . '.g-messages>ul>li { margin-bottom: 2px; padding: 0.4em 7px; border-radius: 4px; background: #ddd; }' . $this->getNewline() .
				$this->getIndention() . '.g-messages>ul>li>.number { color: #888; font-size: 0.9em; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml()
	{
		$messages = $this->getOwnerPlugin()->getOwnerSpec()->messages->getAll();

		if (!count($messages))
			return null;

		$output = '';

		$output .= '<div class="g-messages g-clearfix">' . $this->getNewline();
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