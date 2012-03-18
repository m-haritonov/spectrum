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
			'<script type="text/javascript">
				(function(){
					updateCurrentFinalResult = function()
					{
						var scriptNode = getExecutedScriptNode();
						var targetFinalResultNode = findNearestSpecNode(scriptNode).querySelector(".g-specTitle .finalResult");
						var sourceFinalResultNode = findNearestFinalResultNode(scriptNode);
						setFinalResultTo(targetFinalResultNode, sourceFinalResultNode);
					}

					function getExecutedScriptNode()
					{
						var scripts = document.getElementsByTagName("script");
						return scripts[scripts.length - 1];
					}

					function findNearestSpecNode(startNode)
					{
						var specNode = startNode;
						while (specNode.tagName != "LI")
							specNode = specNode.parentNode;
						return specNode;
					}

					function findNearestFinalResultNode(startNode)
					{
						var finalResultNode = startNode;
						while (finalResultNode.className != "g-finalResult")
							finalResultNode = finalResultNode.parentNode;
						return finalResultNode;
					}

					function setFinalResultTo(targetFinalResultNode, sourceFinalResultNode)
					{
						targetFinalResultNode.className += " " + sourceFinalResultNode.getAttribute("data-resultAlias");
						targetFinalResultNode.innerHTML = sourceFinalResultNode.getAttribute("data-resultTitle");
					}

				})();' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtml($finalResult)
	{
		$resultAlias = $this->getFinalResultAlias($finalResult);
		return
			'<span class="g-finalResult" data-resultAlias="' . htmlspecialchars($resultAlias) . '" data-resultTitle="' . $this->translate($resultAlias) . '">' . $this->getNewline() .
				$this->getIndention() . '<script type="text/javascript">updateCurrentFinalResult();</script>' . $this->getNewline() .
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