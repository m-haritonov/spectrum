<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components;

use spectrum\config;
use spectrum\core\details\MatcherCallInterface;
use spectrum\core\details\PhpErrorInterface;
use spectrum\core\details\UserFailInterface;
use spectrum\core\SpecInterface;

class resultBuffer extends \spectrum\core\plugins\reports\drivers\text\components\component {
	/**
	 * @return null|string
	 */
	static public function getContent(SpecInterface $spec) {
		$results = $spec->getResultBuffer()->getResults();
		if (count($results) == 0) {
			return null;
		}
		
		$title = static::translate('Result buffer');
		$content = '';
		$content .= str_repeat('=', mb_strlen($title, config::getOutputCharset())) . static::getOutputNewline();
		$content .= $title . static::getOutputNewline();
		$content .= str_repeat('=', mb_strlen($title, config::getOutputCharset())) . static::getOutputNewline(2);
		$content .= static::getContentForResults($results);
		return $content;
	}

	/**
	 * @return string
	 */
	static protected function getContentForResults(array $results) {
		$content = '';
		
		$num = 0;
		$resultsCount = count($results);
		foreach ($results as $result) {
			$num++;
			$content .= static::translate('Order') . ': ' . $num . static::getOutputNewline();
			$content .= static::translate('Result, contains in run results buffer') . ': ' . static::getResultValueName($result['result']) . static::getOutputNewline();
			
			if ($result['result'] === false) {
				$content .= static::translate('Fail type') . ': ' . static::translate(static::getFailType($result['details'])) . static::getOutputNewline();
			}
			
			$content .= static::getContentForResultDetails($result['details']);
			if ($num < $resultsCount) {
				$content .= static::getOutputNewline(2);
			}
		}

		return $content;
	}

	/**
	 * @param mixed $result
	 * @return string
	 */
	static protected function getResultValueName($result) {
		if ($result === false) {
			return 'false';
		} else if ($result === true) {
			return 'true';
		} else if ($result === null) {
			return 'null';
		} else {
			return 'unknown';
		}
	}

	/**
	 * @param mixed $details
	 * @return string
	 */
	static protected function getFailType($details) {
		if (is_object($details) && $details instanceof MatcherCallInterface) {
			return 'matcher call fail';
		} else if (is_object($details) && $details instanceof PhpErrorInterface) {
			return 'php error';
		} else if (is_object($details) && $details instanceof UserFailInterface) {
			return 'user fail';
		} else {
			return 'unknown fail';
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