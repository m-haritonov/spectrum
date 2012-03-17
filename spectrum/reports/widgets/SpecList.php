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

namespace net\mkharitonov\spectrum\reports\widgets;
use \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface;
use \net\mkharitonov\spectrum\core\SpecContainerContextInterface;
use \net\mkharitonov\spectrum\core\SpecItemItInterface;
use \net\mkharitonov\spectrum\core\SpecContainerInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class SpecList extends \net\mkharitonov\spectrum\reports\widgets\Widget
{
	static protected $depth;
	static protected $numbers = array();

	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-specList { list-style: none; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList .g-specList { padding-left: 25px; list-style: none; }' . $this->getNewline() .
				$this->getIndention() . ".g-specList>li>.indention { display: inline-block; width: 0; white-space: pre; }" . $this->getNewline() .

				$this->getIndention() . '.g-specList>li>.finalResult { color: #ccc; font-weight: bold; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.finalResult.fail { color: #a31010; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.finalResult.success { color: #009900; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.finalResult.empty { color: #cc9900; }' . $this->getNewline() .

				$this->getIndention() . '.g-specList>li.it { }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li.describe { }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li.context { }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getScripts()
	{
		return
			'<script type="text/javascript">' . $this->getNewline() .
				$this->getIndention() . 'function updateResult(uid, resultLabel, resultName){' . $this->getNewline() .
					$this->getIndention(2) . 'var result = document.querySelector("#" + uid + ">.finalResult");' . $this->getNewline() .
					$this->getIndention(2) . 'result.className += " " + resultLabel;' . $this->getNewline() .
					$this->getIndention(2) . 'result.innerHTML = resultName;' . $this->getNewline() .
				$this->getIndention() . '}' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtmlBegin()
	{
		$output = '';

		if (!$this->getOwnerPlugin()->getOwnerSpec()->getParent())
		{
			static::$depth = 0;
			$output .= $this->getIndention(static::$depth + 1) . '<ol class="g-specList">' . $this->getNewline();
		}

		if (!$this->getOwnerPlugin()->getOwnerSpec()->isAnonymous())
		{
			@static::$numbers[static::$depth]++;

			$output .= $this->getIndention(static::$depth * 2 + 2) . '<li class="' . $this->getSpecCssClass() . '" id="' . $this->getOwnerPlugin()->getOwnerSpec()->selector->getUidForSpec() . '">' . $this->getNewline();
			$output .= $this->getHtmlForSpecTitle();

			if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecContainerInterface || !$this->getOwnerPlugin()->getOwnerSpec()->getParent())
			{
				$output .= $this->getIndention(static::$depth * 2 + 3) . '<ol class="g-specList">' . $this->getNewline();
				static::$depth++;
			}
		}

		return $output;
	}

	protected function getHtmlForSpecTitle()
	{
		return
			$this->getHtmlForCurrentSpecIndention() .
			$this->getHtmlForSpecNumber() . $this->getNewline() .
			$this->getIndention(static::$depth * 2 + 3) . '<span class="name">' . htmlspecialchars($this->getSpecName()) . '</span>' . $this->getNewline() .
			$this->getIndention(static::$depth * 2 + 3) . '<span class="separator"> — </span>' . $this->getNewline() .
			$this->getIndention(static::$depth * 2 + 3) . '<span class="finalResult">wait...</span>' . $this->getNewline();
	}

	protected function getHtmlForSpecNumber()
	{
		return '<span class="number">' . htmlspecialchars($this->getSpecNumber()) . '. </span>';
	}

	protected function getSpecNumber()
	{
		return @static::$numbers[static::$depth];
	}

	protected function getHtmlForCurrentSpecIndention()
	{
		return $this->getIndention(static::$depth * 2 + 3) . str_repeat('<span class="indention">' . $this->getIndention() . '</span>', static::$depth);
	}

	public function getHtmlEnd($finalResult)
	{
		$output = '';

		if (!$this->getOwnerPlugin()->getOwnerSpec()->isAnonymous())
		{
			if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecContainerInterface)
			{
				static::$numbers[static::$depth] = 0;
				static::$depth--;
				$output .= $this->getIndention(static::$depth * 2 + 3) . '</ol>' . $this->getNewline();
			}

			$output .= $this->getScriptForResultUpdate($this->getOwnerPlugin()->getOwnerSpec()->selector->getUidForSpec(), $this->getSpecResultCssClass($finalResult)) . $this->getNewline();
			$output .= $this->getRunDetails($finalResult) . $this->getNewline();
			$output .= $this->getIndention(static::$depth * 2 + 2) . '</li>' . $this->getNewline();
		}

		if (!$this->getOwnerPlugin()->getOwnerSpec()->getParent())
			$output .= $this->getIndention(static::$depth + 1) . '</ol>' . $this->getNewline();

		return $output;
	}

	protected function getRunDetails($finalResult)
	{
		$output = '';

		if ($finalResult === false)
		{
			$runResultsBufferWidget = new \net\mkharitonov\spectrum\reports\widgets\runResultsBuffer\RunResultsBuffer($this->getOwnerPlugin());
			$output .= $runResultsBufferWidget->getHtml() . $this->getNewline();
		}

		$messagesWidget = new \net\mkharitonov\spectrum\reports\widgets\Messages($this->getOwnerPlugin());
		$output .= $this->prependIndentionToEachTagOnNewline($this->trimNewline($messagesWidget->getHtml()), static::$depth * 2 + 3) . $this->getNewline();

		if (trim($output) != '')
			$output = '<div class="runDetails">' . $output . '</div>';

		return $output;
	}

	protected function getScriptForResultUpdate($specUid, $resultLabel)
	{
		// TODO заменить на периодическую проверку по коду в <head>
		return
			'<script type="text/javascript">' .
				'updateResult("' . $specUid . '", "' . $resultLabel . '", "' . $resultLabel . '");' .
			'</script>';
	}

	protected function getSpecName()
	{
		$parent = $this->getOwnerPlugin()->getOwnerSpec()->getParent();
		$name = $this->getOwnerPlugin()->getOwnerSpec()->getName();

		if ($name == '' && $parent && $parent instanceof \net\mkharitonov\spectrum\core\SpecContainerArgumentsProviderInterface)
			return $this->getAdditionalArgumentsDumpOut();
		else
			return $name;
	}

	protected function getAdditionalArgumentsDumpOut()
	{
		$output = '';
		foreach ($this->getOwnerPlugin()->getOwnerSpec()->getAdditionalArguments() as $arg)
			$output .= $arg . ', ';

		return mb_substr($output, 0, -2);
}

	protected function getSpecCssClass()
	{
		if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecContainerDescribeInterface)
			return 'describe';
		else if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecContainerContextInterface)
			return 'context';
		else if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecItemItInterface)
			return 'it';
		else if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecContainerInterface)
			return 'container';
		else if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecItemInterface)
			return 'item';
		else
			return 'spec';
	}

	protected function getSpecResultCssClass($result)
	{
		if ($result === false)
			$name = 'fail';
		else if ($result === true)
			$name = 'success';
		else
			$name = 'empty';

		return $name;
	}
}