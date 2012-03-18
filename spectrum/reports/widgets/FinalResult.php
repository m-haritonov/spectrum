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
class FinalResult extends \net\mkharitonov\spectrum\reports\widgets\Widget
{
	public function getScripts()
	{
		return
			'<script type="text/javascript">' . $this->getNewline() .
				$this->getIndention() . '(function(){' . $this->getNewline() .
					$this->getIndention(2) . 'updateCurrentFinalResult = function(){' . $this->getNewline() .
						$this->getIndention(3) . 'var scriptNode = getExecutedScriptNode();' . $this->getNewline() .
						$this->getIndention(3) . 'var targetFinalResultNode = findNearestSpecNode(scriptNode).querySelector(".g-specTitle .finalResult");' . $this->getNewline() .
						$this->getIndention(3) . 'var sourceFinalResultNode = findNearestFinalResultNode(scriptNode);' . $this->getNewline() .
						$this->getIndention(3) . 'setFinalResultTo(targetFinalResultNode, sourceFinalResultNode);' . $this->getNewline() .
					$this->getIndention(2) . '}' . $this->getNewline(2) .

					$this->getIndention(2) . 'function getExecutedScriptNode(){' . $this->getNewline() .
						$this->getIndention(3) . 'var scripts = document.getElementsByTagName("script");' . $this->getNewline() .
						$this->getIndention(3) . 'return scripts[scripts.length - 1];' . $this->getNewline() .
					$this->getIndention(2) . '}' . $this->getNewline(2) .

					$this->getIndention(2) . 'function findNearestSpecNode(startNode){' . $this->getNewline() .
						$this->getIndention(3) . 'var specNode = startNode;' . $this->getNewline() .
						$this->getIndention(3) . 'while (specNode.tagName != "LI")' . $this->getNewline() .
							$this->getIndention(4) . 'specNode = specNode.parentNode;' . $this->getNewline(2) .
						$this->getIndention(3) . 'return specNode;' . $this->getNewline() .
					$this->getIndention(2) . '}' . $this->getNewline(2) .

					$this->getIndention(2) . 'function findNearestFinalResultNode(startNode){' . $this->getNewline() .
						$this->getIndention(3) . 'var finalResultNode = startNode;' . $this->getNewline() .
						$this->getIndention(3) . 'while (finalResultNode.className != "g-finalResult")' . $this->getNewline() .
							$this->getIndention(4) . 'finalResultNode = finalResultNode.parentNode;' . $this->getNewline(2) .
						$this->getIndention(3) . 'return finalResultNode;' . $this->getNewline() .
					$this->getIndention(2) . '}' . $this->getNewline(2) .

					$this->getIndention(2) . 'function setFinalResultTo(targetFinalResultNode, sourceFinalResultNode){' . $this->getNewline() .
						$this->getIndention(3) . 'targetFinalResultNode.className += " " + sourceFinalResultNode.getAttribute("data-resultAlias");' . $this->getNewline() .
						$this->getIndention(3) . 'targetFinalResultNode.innerHTML = sourceFinalResultNode.getAttribute("data-resultTitle");' . $this->getNewline() .
					$this->getIndention(2) . '}' . $this->getNewline() .

				$this->getIndention() . '})();' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtml($finalResult)
	{
		$resultAlias = $this->getFinalResultAlias($finalResult);
		return
			'<span class="g-finalResult" data-resultAlias="' . htmlspecialchars($resultAlias) . '" data-resultTitle="' . $this->translate($resultAlias) . '">' .
				'<script type="text/javascript">updateCurrentFinalResult();</script>' .
			'</span>';
	}

	protected function getFinalResultAlias($result)
	{
		if ($result === false)
			$alias = 'fail';
		else if ($result === true)
			$alias = 'success';
		else
			$alias = 'empty';

		return $alias;
	}
}