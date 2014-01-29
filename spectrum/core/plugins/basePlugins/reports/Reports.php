<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports;

use spectrum\config;
use spectrum\core\plugins\basePlugins\reports\drivers\html\Html;
use spectrum\core\plugins\basePlugins\reports\drivers\text\Text;
use spectrum\core\plugins\Exception;

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
		$this->getOwnerSpec()->output->put($this->createDriver()->getContentBeforeSpec());
		flush();
	}

	protected function onSpecRunFinish()
	{
		$this->getOwnerSpec()->output->put($this->createDriver()->getContentAfterSpec());
		flush();
	}
	
	protected function createDriver()
	{
		if (mb_strtolower(config::getOutputFormat()) == 'html')
			return new Html($this);
		else if (mb_strtolower(config::getOutputFormat()) == 'text')
			return new Text($this);
		else
			throw new Exception('Output format "' . config::getOutputFormat() . '" is not supported by "Reports" plugin');
	}
}