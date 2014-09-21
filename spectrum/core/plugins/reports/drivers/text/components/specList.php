<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components;

use spectrum\core\SpecInterface;

class specList extends component {
	static protected $depth = 0;
	static protected $previousDepth = 0;
	static protected $numbers = array();
	
	static public function getContentBegin(SpecInterface $spec) {
		$output = '';
		
		if ($spec->getParentSpecs() && !$spec->isAnonymous()) {
			if (!isset(static::$numbers[static::$depth])) {
				static::$numbers[static::$depth] = 0;
			}
			
			static::$numbers[static::$depth]++;
			$output .= static::getOutputIndention(static::$depth) . static::getContentForSpecTitle($spec);

			if ($spec->getChildSpecs()) {
				static::$depth++;
				$output .= static::getOutputNewline();
			}
		}
		
		return $output;
	}

	static public function getContentEnd(SpecInterface $spec) {
		$output = '';
		
		if ($spec->getParentSpecs() && !$spec->isAnonymous()) {
			if ($spec->getChildSpecs()) {
				static::$numbers[static::$depth] = 0;
				static::$depth--;
			} else {
				$output .= static::getContentForRunResult($spec);
				$output .= static::getContentForRunDetails($spec);
				$output .= static::getOutputNewline();
			}
		}
		
		return $output;
	}

	static protected function getContentForSpecTitle(SpecInterface $spec) {
		return (isset(static::$numbers[static::$depth]) ? static::$numbers[static::$depth] . '. ' : '') . static::convertToOutputCharset($spec->getName());
	}
	
	static protected function getContentForRunResult(SpecInterface $spec) {
		return ' - ' . static::callComponentMethod('totalResult', 'getContent', array($spec));
	}

	static protected function getContentForRunDetails(SpecInterface $spec) {
		$componentResults = array();
		if ($spec->getResultBuffer()->getTotalResult() !== true) {
			$componentResults[] = static::callComponentMethod('resultBuffer', 'getContent', array($spec));
		}

		$componentResults[] = static::callComponentMethod('messages', 'getContent', array($spec));

		$output = '';
		$num = 0;
		foreach ($componentResults as $result) {
			if (trim($result) != '') {
				if ($num > 0) {
					$output .= static::getOutputNewline(2);
				}
				
				$output .= static::prependOutputIndentionToEachOutputNewline($result, static::$depth + 1);
				$num++;
			}
		}
		
		if ($output != '') {
			$output = static::getOutputNewline() . $output;
		}
		
		return $output;
	}
}