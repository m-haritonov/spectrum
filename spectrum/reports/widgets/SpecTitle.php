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
class SpecTitle extends \net\mkharitonov\spectrum\reports\widgets\Widget
{
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

	public function getHtmlForSpecTitle()
	{
		return
			'<span class="name">' . htmlspecialchars($this->getSpecName()) . '</span>' . $this->getNewline() .
			'<span class="separator"> — </span>' . $this->getNewline() .
			'<span class="finalResult">wait...</span>' . $this->getNewline();
	}

	public function getHtmlForFinalResult($finalResult)
	{
		$specUid = $this->getOwnerPlugin()->getOwnerSpec()->selector->getUidForSpec();
		$resultLabel = $this->getSpecResultCssClass($finalResult);
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