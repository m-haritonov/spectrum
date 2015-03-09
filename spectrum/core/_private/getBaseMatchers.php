<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\config;

/**
 * @access private
 * @return array
 */
function getBaseMatchers() {
	return array(
		'eq' => config::getCoreFunctionReplacement('\spectrum\core\matchers\eq'),
		'gt' => config::getCoreFunctionReplacement('\spectrum\core\matchers\gt'),
		'gte' => config::getCoreFunctionReplacement('\spectrum\core\matchers\gte'),
		'ident' => config::getCoreFunctionReplacement('\spectrum\core\matchers\ident'),
		'is' => config::getCoreFunctionReplacement('\spectrum\core\matchers\is'),
		'lt' => config::getCoreFunctionReplacement('\spectrum\core\matchers\lt'),
		'lte' => config::getCoreFunctionReplacement('\spectrum\core\matchers\lte'),
		'throwsException' => config::getCoreFunctionReplacement('\spectrum\core\matchers\throwsException'),
	);
}