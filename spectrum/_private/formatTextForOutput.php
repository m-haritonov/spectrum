<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private;

use spectrum\core\config;

/**
 * @access private
 * @param string $text
 * @param int $indentionToRemoveCount
 * @param string $inputIndention
 * @param string $inputNewline
 * @param null|string $outputIndention
 * @param null|string $outputNewline
 * @return string
 */
function formatTextForOutput($text, $indentionToRemoveCount = 0, $inputIndention = "\t", $inputNewline = "\n", $outputIndention = null, $outputNewline = null) {
	if ($outputIndention === null) {
		$outputIndention = config::getOutputIndention();
	}
	
	if ($outputNewline === null) {
		$outputNewline = config::getOutputNewline();
	}
	
	$newText = '';
	$inputIndentionByteCount = mb_strlen($inputIndention, 'us-ascii');
	$textLines = explode($inputNewline, $text);
	$lastKey = count($textLines) - 1;
	foreach ($textLines as $key => $line) {
		$indentionToPrependCount = -$indentionToRemoveCount;
		while (mb_strpos($line, $inputIndention, null, 'us-ascii') === 0) {
			$line = mb_substr($line, $inputIndentionByteCount, mb_strlen($line, 'us-ascii'), 'us-ascii');
			$indentionToPrependCount++;
		}
		
		if ($indentionToPrependCount > 0) {
			$newText .= str_repeat($outputIndention, $indentionToPrependCount);
		}
		
		$newText .= $line;
		
		if ($key < $lastKey) {
			$newText .= $outputNewline;
		}
	}
	
	return $newText;
}