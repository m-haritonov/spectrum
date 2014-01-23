<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables;

class NullVar extends Variable
{
	protected $type = 'null';

	protected function getHtmlForType($variable)
	{
		return null;
	}

	protected function getHtmlForValue($variable)
	{
		return '<span class="value">null</span>';
	}
}