<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\reports;

use spectrum\config;
use spectrum\Exception;

class Reports extends \spectrum\core\plugins\Plugin
{
	static public function getAccessName()
	{
		return 'reports';
	}
	
	static public function getEventListeners()
	{
		return array(
			array('event' => 'onSpecRunStart', 'method' => 'onSpecRunStart', 'order' => 20),
			array('event' => 'onSpecRunFinish', 'method' => 'onSpecRunFinish', 'order' => -20),
		);
	}
	
	protected function onSpecRunStart()
	{
		$driverClass = $this->getDriverClass();
		print $driverClass::getContentBeforeSpec($this->getOwnerSpec());
		flush();
	}

	protected function onSpecRunFinish()
	{
		$driverClass = $this->getDriverClass();
		print $driverClass::getContentAfterSpec($this->getOwnerSpec());
		flush();
	}
	
	protected function getDriverClass()
	{
		$convertLatinCharsToLowerCaseFunction = config::getFunctionReplacement('\spectrum\_internal\convertLatinCharsToLowerCase');
		$outputFormatWithLatinLowerCase = $convertLatinCharsToLowerCaseFunction(config::getOutputFormat());
		
		if ((string) $outputFormatWithLatinLowerCase === 'html')
			return config::getClassReplacement('\spectrum\core\plugins\reports\drivers\html\html');
		else if ((string) $outputFormatWithLatinLowerCase === 'text')
			return config::getClassReplacement('\spectrum\core\plugins\reports\drivers\html\text');
		else
			throw new Exception('Output format "' . config::getOutputFormat() . '" is not supported by "Reports" plugin');
	}
}