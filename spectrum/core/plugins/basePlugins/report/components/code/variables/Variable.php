<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\code\variables;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Variable extends \net\mkharitonov\spectrum\core\plugins\basePlugins\report\Component
{
	protected $type;
	protected $expandedParentSelector = '.g-runResultsBuffer>.results>.result.expand';
	protected $trueResultParentSelector = '.g-runResultsBuffer>.results>.result.true';
	protected $falseResultParentSelector = '.g-runResultsBuffer>.results>.result.false';

	protected $codeComponent;

	public function __construct(\net\mkharitonov\spectrum\core\plugins\basePlugins\report\Report $report)
	{
		parent::__construct($report);
		$this->codeComponent = new \net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\code\Code($this->getReport());
	}

	public function getStyles()
	{
		$componentSelector = '.g-code-variables-' . htmlspecialchars($this->type);

		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . "$componentSelector { font-family: monospace; font-size: 12px; }" . $this->getNewline() .
				$this->getIndention() . "$componentSelector .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; white-space: nowrap; vertical-align: top; }" . $this->getNewline() .
				$this->getIndention() . "$componentSelector .type { font-size: 0.9em; color: rgba(0, 0, 0, 0.6); }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $componentSelector .value { display: inline; overflow: visible; max-width: auto; white-space: normal; }" . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml($variable)
	{
		return '<span class="g-code-variables g-code-variables-' . htmlspecialchars($this->type) . '">' . $this->getHtmlForType($variable) . $this->getHtmlForValue($variable) . '</span>';
	}

	protected function getHtmlForType($variable)
	{
		return '<span class="type">' . htmlspecialchars($this->type) . '</span>';
	}

	protected function getHtmlForValue($variable)
	{
		return ' <span class="value">' . htmlspecialchars($variable) . '</span>';
	}
}