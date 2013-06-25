<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specs;

interface ResultBufferInterface
{
	public function __construct(\spectrum\core\specs\SpecInterface $ownerSpec);
	public function getOwnerSpec();
	
	public function addFailResult($details = null);
	public function addSuccessResult($details = null);
	
	public function getResults();
	public function getTotalResult();
}