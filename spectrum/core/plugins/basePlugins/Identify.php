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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins;
use \net\mkharitonov\spectrum\core\plugins\Exception;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Identify extends \net\mkharitonov\spectrum\core\plugins\Plugin
{
	/*
	 * format: spec<ancstor spec indexes in parent>, example "0x1x24">
	 * format: spec<ancstor spec indexes in parent>, example "0x1x24">_<context stack indexes for each ancestor, example "c0x1c0c">
	 */
	public function getSpecId()
	{
		$stack = $this->getOwnerSpec()->selector->getAncestorsStack();
		$stack[] = $this->getOwnerSpec();

		$uid = 'spec' . $this->getIndexesString($stack);

		if ($this->getOwnerSpec()->isRunning())
		{
			$uid .= '_';
			foreach ($stack as $spec)
				$uid .= 'c' . $this->getIndexesString($spec->selector->getChildRunningContextsStack());
		}

		return $uid;
	}

	public function getSpecById($specId)
	{
		$this->validateSpecId($specId);

		$spec = null;
		foreach ($this->parseSpecIndexesInSpecId($specId) as $num => $specIndex)
		{
			if ($num == 0)
			{
				if ($specIndex == 0)
					$spec = $this->getOwnerSpec()->selector->getRoot();
				else
					throw new Exception('Incorrect spec id "' . $specId . '" (first index in id should be "0")');
			}
			else
				$spec = $spec->selector->getChildByIndex($specIndex);

			if (!$spec)
				throw new Exception('Incorrect spec id "' . $specId . '" (spec with index "' . $specIndex . '" on "' . ($num + 1) . '" position in id not exists)');
		}

		return $spec;
	}

	public function getContextsById($specId)
	{
		// TODO get all ancestors by spec id, get contexts stack for each ancestor
//		$this->validateSpecId($specId);
//
//		$contextIndexes = $this->parseContextIndexesInSpecId($specId);
//		foreach ($contextIndexes as $indexes)
//		{
//			foreach ($indexes as &$index)
//			{
//			}
//		}
//
//		return $contextIndexes;
	}

/**/

	protected function getSpecIndexInParent()
	{
		$parent = $this->getOwnerSpec()->getParent();
		if ($parent)
		{
			foreach ($parent->getSpecs() as $index => $spec)
			{
				if ($spec === $this->getOwnerSpec())
					return $index;
			}
		}

		return null;
	}

	protected function getIndexesString(array $stack)
	{
		$uid = '';
		foreach ($stack as $spec)
			$uid .= (int) $spec->identify->getSpecIndexInParent() . 'x';

		return mb_substr($uid, 0, -1);
	}

	protected function parseSpecIndexesInSpecId($specId)
	{
		$specId = trim($specId);

		if (preg_match('/^spec(\d+(x\d+)*)/is', $specId, $matches))
			return explode('x', $matches[1]);
		else
			return array();
	}

	protected function parseContextIndexesInSpecId($specId)
	{
		$specId = trim($specId);

		if (preg_match('/_((c(\d+(x\d+)*)?)+)/is', $specId, $matches))
		{
			$contextId = $matches[1];

			if (preg_match_all('/c[^c]*/is', $contextId, $matches))
			{
				$result = array();
				foreach ($matches[0] as $indexes)
				{
					$indexes = mb_substr($indexes, 1);

					if ($indexes == '')
						$result[] = array();
					else
						$result[] = explode('x', $indexes);
				}

				return $result;
			}
		}

		return array();
	}

	protected function validateSpecId($specId)
	{
		$specId = trim($specId);

		if (!preg_match('/^spec/s', $specId))
			throw new Exception('Incorrect spec id "' . $specId . '" (id should be started with "spec" string)');

		if (preg_match('/[^specx_0-9]/s', $specId))
			throw new Exception('Incorrect spec id "' . $specId . '" (id should be contains only "spec" string, chars "x", "c", "_" and digits)');

		$indexesRegexp = '(\d+(x\d+)*)';
		if (!preg_match('/^spec' . $indexesRegexp . '(_((c' . $indexesRegexp . '?)+))?$/s', $specId))
			throw new Exception('Incorrect spec id "' . $specId . '" (id should be in format like "spec0x1" or "spec0x1_c0c0")');
	}
}