<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports;

use spectrum\config;
use spectrum\core\plugins\basePlugins\reports\drivers\html\Html;
use spectrum\core\plugins\basePlugins\reports\drivers\text\Text;

class Reports extends \spectrum\core\plugins\Plugin
{
	protected $outputType;
	protected $indention = "\t";
	protected $newline = "\r\n";

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
	
	/**
	 * @param $type "html"|"text"
	 */
	public function setOutputType($type)
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!config::getAllowReportSettingsModify())
			throw new Exception('Reports settings modify deny in config');
		
		$type = strtolower($type);
		
		if ($type != 'html' && $type != 'text')
			throw new Exception('Wrong type "' . $type . '" in method "' . __METHOD__ . '"');
		
		$this->outputType = $type;
	}
	
	public function getOutputType()
	{
		return $this->outputType;
	}
	
/**/
	
	public function setIndention($string)
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!config::getAllowReportSettingsModify())
			throw new Exception('Reports settings modify deny in config');

		$this->indention = $string;
	}

	public function getIndention()
	{
		return $this->indention;
	}

/**/

	public function setNewline($newline)
	{
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!config::getAllowReportSettingsModify())
			throw new Exception('Reports settings modify deny in config');

		$this->newline = $newline;
	}

	public function getNewline()
	{
		return $this->newline;
	}

/**/

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
		if ($this->outputType == 'html')
			return new Html($this);
		else
			return new Text($this);
	}
}