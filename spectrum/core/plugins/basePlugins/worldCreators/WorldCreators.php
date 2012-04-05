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