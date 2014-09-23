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

		$content = '';
		$title = static::translate('Messages');
		$content .= str_repeat('=', mb_strlen($title, config::getOutputCharset())) . static::getOutputNewline();
		$content .= $title . static::getOutputNewline();
		$content .= str_repeat('=', mb_strlen($title, config::getOutputCharset())) . static::getOutputNewline();
		$content .= static::getContentForMessages($messages);
		return $content;
	}
	
	static protected function getContentForMessages($messages) {
		$content = '';
		
		$num = 0;
		$messagesCount = count($messages);
		foreach ($messages as $message) {
			$num++;
			$content .= $num . '. ';
			$content .= static::convertToOutputCharset($message);
			if ($num < $messagesCount) {
				$content .= static::getOutputNewline();
			}
		}

		return $content;
	}
}