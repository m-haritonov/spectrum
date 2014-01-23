<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables;

class BoolVar extends Variable
{
	protected $type = 'bool';

	protected function getHtmlForValue($variable)
	{
		return ' <span class="value">' . ($variable ? 'true' : 'false') . '</span>';
	}
}