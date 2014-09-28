<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports;

use spectrum\config;
use spectrum\Exception;

class Reports extends \spectrum\core\plugins\Plugin {
	/**
	 * @return string
	 */
	static public function getAccessName() {
		return 'reports';
	}

	/**
	 * @return array
	 */
	static public function getEventListeners() {
		return array(
			array('event' => 'onSpecRunStart', 'method' => 'onSpecRunStart', 'order' => 20),
			array('event' => 'onSpecRunFinish', 'method' => 'onSpecRunFinish', 'order' => -20),
		);
	}
	
	protected function onSpecRunStart() {
		$driverClass = $this->getDriverClass();
		print $driverClass::getContentBeforeSpec($this->getOwnerSpec());
		flush();
	}

	protected function onSpecRunFinish() {
		$driverClass = $this->getDriverClass();
		print $driverClass::getContentAfterSpec($this->getOwnerSpec());
		flush();
	}

	/**
	 * @return string
	 */
	protected function getDriverClass() {
		$convertLatinCharsToLowerCaseFunction = config::getFunctionReplacement('\spectrum\_internals\convertLatinCharsToLowerCase');
		$outputFormatWithLatinLowerCase = $convertLatinCharsToLowerCaseFunction(config::getOutputFormat());
		
		if ($outputFormatWithLatinLowerCase === 'html') {
			return config::getClassReplacement('\spectrum\core\plugins\reports\drivers\html\html');
		} else if ($outputFormatWithLatinLowerCase === 'text') {
			return config::getClassReplacement('\spectrum\core\plugins\reports\drivers\text\text');
		} else {
			throw new Exception('Output format "' . config::getOutputFormat() . '" is not supported by "Reports" plugin');
		}
	}
}