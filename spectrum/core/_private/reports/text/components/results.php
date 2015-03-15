<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\text\components;

use spectrum\core\config;
use spectrum\core\models\details\MatcherCallInterface;
use spectrum\core\models\details\PhpErrorInterface;
use spectrum\core\models\details\UserFailInterface;
use spectrum\core\models\ResultInterface;
use spectrum\core\models\SpecInterface;

class results extends \spectrum\core\_private\reports\text\components\component {
	/**
	 * @return null|string
	 */
	static public function getContent(SpecInterface $spec) {
		$contentForResults = static::getContentForResults($spec->getResults()->getAll());
		
		if (trim($contentForResults) == '') {
			return null;
		}
		
		$title = static::translate('Results');
		$content = '';
		$content .= str_repeat('=', mb_strlen($title, config::getOutputCharset())) . static::getOutputNewline();
		$content .= $title . static::getOutputNewline();
		$content .= str_repeat('=', mb_strlen($title, config::getOutputCharset())) . static::getOutputNewline(2);
		$content .= $contentForResults;
		return $content;
	}

	/**
	 * @param ResultInterface[] $results
	 * @return string
	 */
	static protected function getContentForResults(array $results) {
		$content = '';
		
		$num = 0;
		$hasPreviousResult = false;
		foreach ($results as $result) {
			$num++;
			$value = $result->getValue();
			
			if (!(($value === false && config::hasOutputResults('all fail')) || ($value === true && config::hasOutputResults('all success')) || ($value === null && config::hasOutputResults('all empty')) || ($value !== false && $value !== true && $value !== null && config::hasOutputResults('all unknown')))) {
				continue;
			}
			
			if ($hasPreviousResult) {
				$content .= static::getOutputNewline(2);
			}
			
			$content .= static::translate('Order') . ': ' . $num . static::getOutputNewline();
			$content .= static::translate('Result') . ': ' . static::getResultValueName($value) . static::getOutputNewline();
			$content .= static::translate('Type') . ': ' . static::translate(static::getType($result->getDetails())) . static::getOutputNewline();
			$content .= static::getContentForResultDetails($result->getDetails());
			
			$hasPreviousResult = true;
		}

		return $content;
	}

	/**
	 * @param mixed $result
	 * @return string
	 */
	static protected function getResultValueName($result) {
		if ($result === false) {
			return 'fail';
		} else if ($result === true) {
			return 'success';
		} else if ($result === null) {
			return 'empty';
		} else {
			return 'unknown';
		}
	}

	/**
	 * @param mixed $details
	 * @return string
	 */
	static protected function getType($details) {
		if (is_object($details) && $details instanceof MatcherCallInterface) {
			return 'matcher call';
		} else if (is_object($details) && $details instanceof PhpErrorInterface) {
			return 'php error';
		} else if (is_object($details) && $details instanceof UserFailInterface) {
			return 'user fail';
		} else if (is_object($details) && $details instanceof \Exception) {
			return 'exception';
		} else {
			return 'unknown';
		}
	}

	/**
	 * @param mixed $details
	 * @return string
	 */
	static protected function getContentForResultDetails($details) {
		if (is_object($details) && $details instanceof MatcherCallInterface) {
			return static::callComponentMethod('details\matcherCall', 'getContent', array($details));
		} else if (is_object($details) && $details instanceof PhpErrorInterface) {
			return static::callComponentMethod('details\phpError', 'getContent', array($details));
		} else if (is_object($details) && $details instanceof UserFailInterface) {
			return static::callComponentMethod('details\userFail', 'getContent', array($details));
		} else {
			return static::callComponentMethod('details\unknown', 'getContent', array($details));
		}
	}
}