<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

class CodeFormatter
{
	protected $highlightCode = true;
	protected $highlightSpaces = true;

	protected $removeLeadingTabColumns = true;
	protected $wrapTapsToTag = true;
	protected $tabWrapperCssClass = 'tab';

	protected $indentionType = 'tabs';
	protected $spacesIndentionSize = 4;

	public function setHighlightCode($highlightCode)
	{
		$this->highlightCode = $highlightCode;
	}

	public function getHighlightCode()
	{
		return $this->highlightCode;
	}

	public function setHighlightSpaces($highlightSpaces)
	{
		$this->highlightSpaces = $highlightSpaces;
	}

	public function getHighlightSpaces()
	{
		return $this->highlightSpaces;
	}

	public function setIndentionType($indentionType)
	{
		$this->indentionType = $indentionType;
	}

	public function getIndentionType()
	{
		return $this->indentionType;
	}

	public function setRemoveLeadingTabColumns($removeLeadingTabColumns)
	{
		$this->removeLeadingTabColumns = $removeLeadingTabColumns;
	}

	public function getRemoveLeadingTabColumns()
	{
		return $this->removeLeadingTabColumns;
	}

	public function setSpacesIndentionSize($spacesIndentionSize)
	{
		$this->spacesIndentionSize = $spacesIndentionSize;
	}

	public function getSpacesIndentionSize()
	{
		return $this->spacesIndentionSize;
	}

	public function setWrapTapsToTag($wrapTapsToTag)
	{
		$this->wrapTapsToTag = $wrapTapsToTag;
	}

	public function getWrapTapsToTag()
	{
		return $this->wrapTapsToTag;
	}

	public function setTabWrapperCssClass($tabWrapperCssClass)
	{
		$this->tabWrapperCssClass = $tabWrapperCssClass;
	}

	public function getTabWrapperCssClass()
	{
		return $this->tabWrapperCssClass;
	}

/**/

	public function format($code)
	{
		if ($this->removeLeadingTabColumns)
			$code = $this->doRemoveLeadingTabs($code);

		if ($this->highlightCode)
			$code = $this->doHighlightCode($code);

		if ($this->indentionType == 'spaces')
			$code = $this->doLeadingTabsToSpaces($code);
		else
			$code = $this->doLeadingSpacesToTabs($code);

		if ($this->wrapTapsToTag)
			$code = str_replace("\t", '<span class="' . htmlspecialchars($this->tabWrapperCssClass) . '">' . "\t" . '</span>', $code);

		if ($this->highlightSpaces)
			$code = $this->doHighlightSpaces($code);

		return $code;
	}

	protected function doRemoveLeadingTabs($text)
	{
		$newText = explode("\n", $text);

		foreach ($newText as $key => $line)
		{
			$newLine = preg_replace('/(^|\n)\t/s', '$1', $line);
			if ($newLine == $line && $line != '')
				return $text;

			$newText[$key] = $newLine;
		}

		$newText = implode("\n", $newText);

		if (preg_match('/(^|\n)\t/s', $newText))
			return $this->doRemoveLeadingTabs($newText);
		else
			return $newText;
	}

	protected function doHighlightCode($code)
	{
		if (!preg_match('/^\s*\<\?php/is', $code))
		{
			$code = "<?php\r\n" . $code;
			$openTagPrepended = true;
		}
		else
			$openTagPrepended = false;

		$code = highlight_string($code, true);

		// Return back formatting
		$code = str_replace('&nbsp;', ' ', $code);
		$code = preg_replace('/\<span style\=\"background\:[^\;\>]+\;?\"\>(\s*)\<\/span\>/is', '$1', $code);
		$code = str_replace('<br />', "\r\n", $code);

		$code = str_replace(array('<code>', '</code>'), '', $code);

		// Remove root wrapper
		$code = preg_replace('/^\<span[^\>]*\>/is', '', $code);
		$code = preg_replace('/<\/span>$/is', '', $code);

		if ($openTagPrepended)
		{
			$openTagRegexp = $this->escapeToRegexp('<span style="color: #0000BB">');
			$code = preg_replace('/^(\s*' . $openTagRegexp . ')\&lt\;\?php(?:\r|\n)+/is', '$1$2', $code);
		}

		$code = preg_replace('/^(\r|\n)+|(\r|\n)+$/s', '', $code);

		// Return root wrapper
		$code = preg_replace('/^(\s*)/s', '$1<span style="color: #000000">', $code);
		$code = preg_replace('/(\s*)$/s', '</span>$1', $code);

		return $code;
	}

	/**
	 * spaces - yellow
	 * tabs - green
	 */
	protected function doHighlightSpaces($text)
	{
		$text = preg_split('/(\>|\<)/s', $text, -1, \PREG_SPLIT_DELIM_CAPTURE);

		$isTag = false;
		foreach ($text as $key => $line)
		{
			if ($line == '<')
			{
				$isTag = true;
				continue;
			}
			else if ($line == '>')
			{
				$isTag = false;
				continue;
			}

			if (!$isTag)
			{
				$line = preg_replace('/( )/s', '<span style="background: #ffffcc;">$1</span>', $line);
				$line = preg_replace('/(\t)/s', '<span style="background: #ccffcc;">$1</span>', $line);
				$text[$key] = $line;
			}
		}

		return implode('', $text);
	}

	//function leadingHtmlSpacesToTabs($html)
	//{
	//	$brRegexp = escapeToRegexp('<br />');
	//	$spacesRegexp = escapeToRegexp('&nbsp;&nbsp;&nbsp;&nbsp');
	//	return preg_replace('/((?:^|' . $brRegexp . ')(?:' . $spacesRegexp . ')*) {4}/s', "$1\t", $html);
	//}

	protected function doLeadingSpacesToTabs($text)
	{
		$regexp = '/((?:^|\n)\t*) {' . $this->spacesIndentionSize . '}/s';
		while (preg_match($regexp, $text))
			$text = preg_replace($regexp, "$1\t", $text);

		return $text;
	}

	protected function doLeadingTabsToSpaces($text)
	{
		$regexp = '/((?:^|\n)(?: {' . $this->spacesIndentionSize . '})*)\t/s';
		while (preg_match($regexp, $text))
			$text = preg_replace($regexp, '$1' . str_repeat(' ', $this->spacesIndentionSize), $text);

		return $text;
	}

	protected function escapeToRegexp($text, $newlineToAnySpaces = true)
	{
		$text = preg_quote($text, '/');
		if ($newlineToAnySpaces)
			$text = preg_replace('/(\r|\n)+/', '\s*', $text);

		return $text;
	}
}