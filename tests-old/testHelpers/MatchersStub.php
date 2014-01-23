<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\testHelpers;

class MatchersStub extends \spectrum\core\plugins\basePlugins\Matchers
{
	public function getFromSelfOrAncestor($key)
	{
		return $this->get($key);
	}

	public function getAllPrependAncestors()
	{
		return $this->getAll();
	}

	public function getAllAppendAncestors()
	{
		return $this->getAll();
	}

	public function callMatcher($name, array $args = array())
	{
		if ($name == 'true')
			return true;
		else
			return false;
	}
}