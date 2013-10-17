<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\variables;

use spectrum\core\plugins\basePlugins\reports\drivers\DriverInterface;

abstract class Variable extends \spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\Widget
{
	protected $type;
	protected $expandedParentSelector = '.g-resultBuffer>.results>.result.expand';
	protected $trueResultParentSelector = '.g-resultBuffer>.results>.result.true';
	protected $falseResultParentSelector = '.g-resultBuffer>.results>.result.false';

	protected $depth;

	public function __construct(DriverInterface $ownerDriver, $depth = 0)
	{
		parent::__construct($ownerDriver);
		$this->depth = $depth;
	}

	public function getStyles()
	{
		$widgetSelector = '.g-code-variables-' . htmlspecialchars($this->type);

		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . "$widgetSelector { font-size: 12px; }" . $this->getNewline() .
				$this->getIndention() . "$widgetSelector .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: top; }" . $this->getNewline() .
				$this->getIndention() . "$widgetSelector .type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }" . $this->getNewline() .
				$this->getIndention() . "$this->expandedParentSelector $widgetSelector .value { overflow: visible; max-width: none; white-space: normal; }" . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml($variable)
	{
		return '<span class="g-code-variables-' . htmlspecialchars($this->type) . ' g-code-variables">' . $this->getHtmlForType($variable) . $this->getHtmlForValue($variable) . '</span>';
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