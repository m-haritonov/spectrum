<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\details;

use \spectrum\core\details\MatcherCallInterface;

class matcherCall extends \spectrum\core\plugins\reports\drivers\text\components\component {
	static public function getContent(MatcherCallInterface $details) {
		$contentForMatcherException = static::getContentForMatcherException($details);
		return
			static::getContentForEvaluatedValues($details) . static::getOutputNewline() .
			$contentForMatcherException . ($contentForMatcherException != '' ? static::getOutputNewline() : '') .
			static::getContentForSource($details);
	}

	static protected function getContentForEvaluatedValues(MatcherCallInterface $details) {
		$content = '';
		$content .= static::translate('Evaluated values') . ': ';
		$content .= static::callComponentMethod('code\method', 'getContent', array('be', array($details->getTestedValue())));

		if ($details->getNot()) {
			$content .= static::callComponentMethod('code\operator', 'getContent', array('->', 'us-ascii'));
			$content .= static::callComponentMethod('code\property', 'getContent', array('not', 'us-ascii'));
		}

		$content .= static::callComponentMethod('code\operator', 'getContent', array('->', 'us-ascii'));
		$content .= static::callComponentMethod('code\method', 'getContent', array($details->getMatcherName(), $details->getMatcherArguments()));
		return $content;
	}
	
	static protected function getContentForMatcherException(MatcherCallInterface $details) {
		if ($details->getMatcherException() === null) {
			return null;
		}
		
		return static::translate('Matcher exception') . ': ' . static::callComponentMethod('code\variable', 'getContent', array($details->getMatcherException()));
	}
	
	static protected function getContentForSource(MatcherCallInterface $details) {
		return
			static::translate('Source') . ': ' .
			static::translate('file') . ' ' . '"' . static::convertToOutputCharset($details->getFile(), 'utf-8') . '", ' .
			static::translate('line') . ' ' . $details->getLine();
	}
}