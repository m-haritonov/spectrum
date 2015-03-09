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
		'eq' => config::getFunctionReplacement('\spectrum\core\matchers\eq'),
		'gt' => config::getFunctionReplacement('\spectrum\core\matchers\gt'),
		'gte' => config::getFunctionReplacement('\spectrum\core\matchers\gte'),
		'ident' => config::getFunctionReplacement('\spectrum\core\matchers\ident'),
		'is' => config::getFunctionReplacement('\spectrum\core\matchers\is'),
		'lt' => config::getFunctionReplacement('\spectrum\core\matchers\lt'),
		'lte' => config::getFunctionReplacement('\spectrum\core\matchers\lte'),
		'throwsException' => config::getFunctionReplacement('\spectrum\core\matchers\throwsException'),
	);
}