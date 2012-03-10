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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins\stack;
use net\mkharitonov\spectrum\core\plugins\Exception;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Stack extends \net\mkharitonov\spectrum\core\plugins\Plugin
{
	protected $items = array();

	public function remove($key)
	{
		return $this->removeByKey($key);
	}

	protected function removeByKey($key)
	{
		$value = $this->items[$key];
		unset($this->items[$key]);
		return $value;
	}

	protected function removeByValue($value, $onlyIdentical = true)
	{
		foreach ($this->items as $key => $val)
		{
			if ($val === $value || (!$onlyIdentical && $val == $value))
				return $this->removeByKey($key);
		}

		return null;
	}

	protected function removeByValueClassName($className)
	{
		foreach ($this->items as $key => $val)
		{
			if (is_object($val) && get_class($val) == $className)
				return $this->removeByKey($key);
		}

		return null;
	}

	public function removeAll()
	{
		$this->items = array();
	}

	public function isExists($key)
	{
		return array_key_exists($key, $this->items);
	}

	public function isExistsCascade($key)
	{
		$stack = $this->getOwnerSpec()->selector->getAncestorsWithRunningContextsStack();
		$stack[] = $this->getOwnerSpec();
		$stack = array_reverse($stack);

		$accessName = $this->accessName;
		foreach ($stack as $spec)
		{
			if ($spec->$accessName->isExists($key))
				return true;
		}

		return false;
	}

	public function get($key)
	{
		if ($this->isExists($key))
			return $this->items[$key];
		else
			throw new Exception('Item "' . $key . '" not exists in plugin with access name "' . $this->accessName . '"');
	}

	public function getCascadeThroughRunningContexts($key)
	{
		$stack = $this->getOwnerSpec()->selector->getAncestorsWithRunningContextsStack();
		$stack[] = $this->getOwnerSpec();
		$stack = array_reverse($stack);

		$accessName = $this->accessName;
		foreach ($stack as $spec)
		{
			if ($spec->$accessName->isExists($key))
				return $spec->$accessName->get($key);
		}

		return $this->doNotExistsInCascade($key);
	}

	protected function doNotExistsInCascade($key)
	{
		throw new Exception('Item "' . $key . '" not exists in plugin with access name "' . $this->accessName . '"');
	}

	public function getAll()
	{
		return $this->items;
	}

	/**
	 * Get from parent to child
	 */
	public function getAllPrependAncestorsWithRunningContexts()
	{
		$stack = $this->getOwnerSpec()->selector->getAncestorsWithRunningContextsStack();
		$stack[] = $this->getOwnerSpec();

		$accessName = $this->accessName;
		$result = array();
		foreach ($stack as $spec)
		{
			$result = array_merge($result, $spec->$accessName->getAll());
		}

		return $result;
	}

	/**
	 * Get from child to parent
	 */
	public function getAllAppendAncestorsWithRunningContexts()
	{
		$stack = $this->getOwnerSpec()->selector->getAncestorsWithRunningContextsStack();
		$stack[] = $this->getOwnerSpec();

		$accessName = $this->accessName;
		$result = array();
		foreach ($stack as $spec)
		{
			$result = array_merge($spec->$accessName->getAll(), $result);
		}

		return $result;
	}
}