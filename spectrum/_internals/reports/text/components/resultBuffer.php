<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals\reports\text\components;

use spectrum\config;
use spectrum\core\details\MatcherCallInterface;
use spectrum\core\details\PhpErrorInterface;
use spectrum\core\details\UserFailInterface;
use spectrum\core\SpecInterface;

class resultBuffer extends \spectrum\_internals\reports\text\components\component {
	/**
	 * @return null|string
	 */
	static public function getContent(SpecInterface $spec) {
		$contentForResults = static::getContentForResults($spec->getResultBuffer()->getResults());
		
		if (trim($contentForResults) == '') {
			return null;
		}
		
		$title = static::translate('Result buffer');
		$content = '';
		$content .= str_repeat('=', mb_strlen($title, config::getOutputCharset())) . static::getOutputNewline();
		$content .= $title . static::getOutputNewline();
		$content .= str_repeat('=', mb_strlen($title, config::getOutputCharset())) . static::getOutputNewline(2);
		$content .= $contentForResults;
		return $content;
	}

	/**
	 * @return string
	 */
	static protected function getContentForResults(array $results) {
		$content = '';
		
		$num = 0;
		$hasPreviousResult = false;
		foreach ($results as $result) {
			$num++;
			
			if (!(($result['result'] === false && config::hasOutputResultBufferElements('all fail')) || ($result['result'] === true && config::hasOutputResultBufferElements('all success')) || ($result['result'] === null && config::hasOutputResultBufferElements('all empty')) || ($result['result'] !== false && $result['result'] !== true && $result['result'] !== null && config::hasOutputResultBufferElements('all unknown')))) {
				continue;
			}
			
			if ($hasPreviousResult) {
				$content .= static::getOutputNewline(2);
			}
			
			$content .= static::translate('Order') . ': ' . $num . static::getOutputNewline();
			$content .= static::translate('Result') . ': ' . static::getResultValueName($result['result']) . static::getOutputNewline();
			$content .= static::translate('Type') . ': ' . static::translate(static::getType($result['details'])) . static::getOutputNewline();
			$content .= static::getContentForResultDetails($result['details']);
			
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