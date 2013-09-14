<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins;

interface PluginInterface
{
	/**
	 * @return null|string
	 */
	static public function getAccessName();

	/**
	 * @return string firstAccess, everyAccess
	 */
	static public function getActivateMoment();

	/**
	 * @return array Value example:
	 *               array(
	 *                   array('event' => 'onEndingSpecExecute', 'method' => 'onEndingSpecExecute', 'order' => 100),
	 *                   array('event' => 'onEndingSpecExecuteBefore', 'method' => 'onEndingSpecExecuteBefore', 'order' => 100),
	 *                   array('event' => 'onEndingSpecExecuteAfter', 'method' => 'onEndingSpecExecuteAfter', 'order' => -100),
	 *               );
	 * 
	 *               Available events:
	 *               onSpecConstruct
	 * 
	 *               onRootSpecRunBefore
	 *               onRootSpecRunAfter
	 * 
	 *               onSpecRunStart
	 *               onSpecRunFinish
	 * 
	 *               onEndingSpecExecute
	 *               onEndingSpecExecuteBefore
	 *               onEndingSpecExecuteAfter
	 * 
	 *               onTestFunctionCallBefore
	 *               onTestFunctionCallAfter
	 * 
	 *               onMatcherCallBefore
	 *               onMatcherCallAfter
	 */
	static public function getEventListeners();
	
	public function __construct(\spectrum\core\SpecInterface $ownerSpec);
	public function getOwnerSpec();
}