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

namespace net\mkharitonov\spectrum\reports\widgets\runResultsBuffer\details;
use \net\mkharitonov\spectrum\core\asserts\MatcherCallDetailsInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Details extends \net\mkharitonov\spectrum\reports\widgets\Widget
{
	/**
	 * @var \net\mkharitonov\spectrum\reports\widgets\code\Code
	 */
	protected $codeWidget;

	public function __construct(\net\mkharitonov\spectrum\reports\Plugin $ownerPlugin)
	{
		parent::__construct($ownerPlugin);
		$this->codeWidget = $this->getOwnerPlugin()->createWidget('code');
	}

	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer-details { padding: 7px; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}
}