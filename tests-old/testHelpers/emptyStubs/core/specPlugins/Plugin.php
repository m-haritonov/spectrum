<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\testHelpers\emptyStubs\core\plugins;

use spectrum\core\plugins\PluginInterface;

class Plugin implements PluginInterface
{
	public function __construct(\spectrum\core\SpecInterface $ownerSpec, $accessName){}
	public function getOwnerSpec(){}
	public function getAccessName(){}
}