<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components;

use spectrum\config;
use spectrum\core\SpecInterface;

class messages extends component {
	static public function getContent(SpecInterface $spec) {
		$messages = $spec->messages->getAll();

		if (!count($messages)) {
			return null;
		}

		$output = '';
		$title = static::translate('Messages');
		$output .= str_repeat('=', mb_strlen($title, config::getOutputCharset())) . static::getOutputNewline();
		$output .= $title . static::getOutputNewline();
		$output .= str_repeat('=', mb_strlen($title, config::getOutputCharset())) . static::getOutputNewline();
		$output .= static::getContentForMessages($messages);
		return $output;
	}
	
	static protected function getContentForMessages($messages) {
		$output = '';
		
		$num = 0;
		$messagesCount = count($messages);
		foreach ($messages as $message) {
			$num++;
			$output .= $num . '. ';
			$output .= static::convertToOutputCharset($message);
			if ($num < $messagesCount) {
				$output .= static::getOutputNewline();
			}
		}

		return $output;
	}
}