<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals\reports\text\components;

use spectrum\core\SpecInterface;

class specList extends component {
	/**
	 * @var int
	 */
	static protected $depth = 0;

	/**
	 * @var array
	 */
	static protected $numbers = array();

	/**
	 * @return string
	 */
	static public function getContentBegin(SpecInterface $spec) {
		$content = '';
		
		if ($spec->getParentSpecs() && !$spec->isAnonymous()) {
			if (!isset(static::$numbers[static::$depth])) {
				static::$numbers[static::$depth] = 0;
			}
			
			static::$numbers[static::$depth]++;
			$content .= static::getOutputIndention(static::$depth) . static::getContentForSpecTitle($spec);

			if ($spec->getChildSpecs()) {
				static::$depth++;
				$content .= static::getOutputNewline();
			}
		}
		
		return $content;
	}

	/**
	 * @return string
	 */
	static public function getContentEnd(SpecInterface $spec) {
		$content = '';
		
		if ($spec->getParentSpecs() && !$spec->isAnonymous()) {
			if ($spec->getChildSpecs()) {
				static::$numbers[static::$depth] = 0;
				static::$depth--;
			} else {
				$content .= static::getContentForRunResult($spec);
				$content .= static::getContentForRunDetails($spec);
				$content .= static::getOutputNewline();
			}
		}
		
		return $content;
	}

	/**
	 * @return string
	 */
	static protected function getContentForSpecTitle(SpecInterface $spec) {
		return (isset(static::$numbers[static::$depth]) ? static::$numbers[static::$depth] . '. ' : '') . static::convertToOutputCharset($spec->getName());
	}

	/**
	 * @return string
	 */
	static protected function getContentForRunResult(SpecInterface $spec) {
		return ' - ' . static::callComponentMethod('totalResult', 'getContent', array($spec));
	}

	/**
	 * @return string
	 */
	static protected function getContentForRunDetails(SpecInterface $spec) {
		$componentResults = array();
		$componentResults[] = static::callComponentMethod('resultBuffer', 'getContent', array($spec));
		$componentResults[] = static::callComponentMethod('messages', 'getContent', array($spec));

		$content = '';
		$num = 0;
		foreach ($componentResults as $result) {
			if (trim($result) != '') {
				if ($num > 0) {
					$content .= static::getOutputNewline(2);
				}
				
				$content .= static::prependOutputIndentionToEachOutputNewline($result, static::$depth + 1);
				$num++;
			}
		}
		
		if ($content != '') {
			$content = static::getOutputNewline() . $content;
		}
		
		return $content;
	}
}