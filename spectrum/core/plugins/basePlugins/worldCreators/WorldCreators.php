<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\worldCreators;
use spectrum\core\plugins\Exception;

abstract class WorldCreators extends \spectrum\core\plugins\basePlugins\stack\Indexed
{
	public function add($callback, $type = 'each')
	{
		if (!in_array($type, array('each')))
			throw new Exception('Wrong world creator type (allowed "each", but "' . $type . '" given)');

		parent::add(array(
			'callback' => $callback,
			'type' => $type,
		));

		return $callback;
	}

	public function remove($indexOrCallback)
	{
		if (is_int($indexOrCallback))
			return parent::removeByKey($indexOrCallback);
		else
			return parent::removeByValue($indexOrCallback);
	}

	abstract public function applyToWorld($world);
}