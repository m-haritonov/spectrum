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
class Selector extends \net\mkharitonov\spectrum\core\plugins\Plugin
{
	public function getRoot()
	{
		$root = $this->getOwnerSpec();
		while ($root->getParent())
		{
			$root = $root->getParent();
		}

		return $root;
	}
	
	public function getNearestNotContextAncestor()
	{
		$parent = $this->getOwnerSpec();
		while ($parent = $parent->getParent())
		{
			if ($parent instanceof \net\mkharitonov\spectrum\core\SpecContainerInterface && !($parent instanceof \net\mkharitonov\spectrum\core\SpecContainerContextInterface))
				return $parent;
		}

		return null;
	}

	/**
	 * @return array
	 */
	public function getNotContextSpecsUpToNearestNotContextAncestor()
	{
		$specs = array();

		$parent = $this->getOwnerSpec();
		while ($parent)
		{
			$specs = array_merge($parent->selector->getNotContextChildren(), $specs);

			// Not context come
			if (!($parent instanceof \net\mkharitonov\spectrum\core\SpecContainerContextInterface))
				return $specs;

			$parent = $parent->getParent();
		}

		return $specs;
	}

	public function getEnabledChildren()
	{
		$enabledSpecs = array();

		foreach ($this->getSpecs() as $spec)
		{
			if ($spec->isEnabled())
				$enabledSpecs[] = $spec;
		}

		return $enabledSpecs;
	}

	public function hasChildContexts()
	{
		foreach ($this->getSpecs() as $spec)
		{
			if ($spec instanceof \net\mkharitonov\spectrum\core\SpecContainerContextInterface)
				return true;
		}

		return false;
	}

	public function getNotContextChildren()
	{
		$specs = array();
		foreach ($this->getSpecs() as $spec)
		{
			if (!($spec instanceof \net\mkharitonov\spectrum\core\SpecContainerContextInterface))
				$specs[] = $spec;
		}

		return $specs;
	}

	/**
	 * @return SpecContainerContext[]
	 */
	public function getChildContexts()
	{
		$result = array();
		foreach ($this->getSpecs() as $spec)
		{
			if ($spec instanceof \net\mkharitonov\spectrum\core\SpecContainerContextInterface)
				$result[] = $spec;
		}

		return $result;
	}

	public function getChildRunningContext()
	{
		if (!($this->getOwnerSpec() instanceof \net\mkharitonov\spectrum\core\SpecContainerInterface))
			return null;

		foreach ($this->getSpecs() as $spec)
		{
			if ($spec instanceof \net\mkharitonov\spectrum\core\SpecContainerContextInterface && $spec->isRunning())
			{
				return $spec;
			}
		}

		return null;
	}
	
	public function getChildRunningContextsStack()
	{
		$stack = array();

		$childRunningContext = $this->getChildRunningContext();
		while ($childRunningContext)
		{
			$stack[] = $childRunningContext;
			$childRunningContext = $childRunningContext->selector->getChildRunningContext();
		}

		return $stack;
	}

	public function getDeepChildRunningContext()
	{
		$childRunningContext = $this->getChildRunningContext();

		if ($childRunningContext && $childRunningContext->selector->hasChildContexts())
			return $childRunningContext->selector->getDeepChildRunningContext();
		else
			return $childRunningContext;
	}

	public function getAncestorsStack()
	{
		$stack = array();

		$parent = $this->getOwnerSpec();
		while ($parent = $parent->getParent())
		{
			$stack[] = $parent;
		}

		return array_reverse($stack);
	}


	public function getAncestorsWithRunningContextsStack()
	{
		$stack = array();
		foreach ($this->getAncestorsStack() as $spec)
		{
			$stack[] = $spec;
			$stack = array_merge($stack, $spec->selector->getChildRunningContextsStack());
		}

		return $stack;
	}

/*	public function getAncestorRunningContextsStack()
	{
		$stack = array();
		foreach ($this->getAncestorsStack() as $spec)
		{
			$stack = array_merge($stack, $spec->selector->getChildRunningContextsStack());
		}

		return $stack;
	}*/

	/**
	 * Return all find specs with same name.
	 * @return array
	 */
	public function getChildrenWithName($name)
	{
		$findSpecs = array();

		foreach ($this->getSpecs() as $index => $spec)
		{
			if ($spec->getName() == $name)
			{
				$findSpecs[$index] = $spec;
			}
		}

		return $findSpecs;
	}

	/**
	 * Return first find spec with same name.
	 */
	public function getChildByName($name)
	{
		foreach ($this->getSpecs() as $spec)
		{
			if ($spec->getName() == $name)
				return $spec;
		}

		return null;
	}

	public function getChildByIndex($index)
	{
		$specs = $this->getSpecs();
		if (array_key_exists($index, $specs))
			return $specs[$index];
		else
			return null;
	}

	public function getUidForSpec()
	{
		$stack = $this->getAncestorsStack();
		$stack[] = $this->getOwnerSpec();
		return 'spec' . $this->getUidIndexes($stack);
	}

	// TODO rename to "getUidForRunningContextStack"
/*	public function getUidInContext()
	{
		if (!$this->isRunning())
			throw new Exception('getUidInContext() method works only for running specs');

		// TODO use getAncestorsWithRunningContextsStack() to identify context
		// TODO context uid - context_0_0_0_context_1_2_0 (context_ancestorIndex_contextIndex_contextIndex) - append "context_*" for each ancestor
		$contextsStack = $this->selector->getAncestorRunningContextsStack();
		return $this->getUid() . '_context' . $this->getUidIndexes($contextsStack, true);
	}*/

	protected function getUidIndexes(array $stack, $omitSelf = false)
	{
		$uid = '';
		foreach ($stack as $spec)
		{
			if ($omitSelf && $spec === $this->getOwnerSpec())
				continue;

			$uid .= '_' . (int) $spec->selector->getIndexInParent();
		}

		return $uid;
	}

	/**
	 * @param string $uid Any spec uid
	 */
	public function getSpecByUid($uid)
	{
		$spec = $this->getRoot();
		foreach ($this->parseUidSpecIndexes($uid) as $num => $specIndex)
		{
			$spec = $spec->selector->getChildByIndex($specIndex);
			if (!$spec)
				throw new Exception('Can\'t find spec with index "' . $specIndex . '" on "' . ($num + 1) . '" position in uid "' . $uid . '"');
		}

		return $spec;
	}

	protected function parseUidSpecIndexes($uid)
	{
		$uid = trim($uid);

		if (!preg_match('/^spec((_[\d]+)+)$/is', $uid, $matches))
			throw new Exception('Incorrect spec uid "' . $uid . '"');

		$ancestorSpecIndexes = mb_substr($matches[1], 1);
		$ancestorSpecIndexes = explode('_', $ancestorSpecIndexes);

		if ($ancestorSpecIndexes[0] != '0')
			throw new Exception('First spec index in uid should be "0" always');
		else
			unset($ancestorSpecIndexes[0]);

		return $ancestorSpecIndexes;
	}

	public function getIndexInParent()
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
	
	/**
	 * @return \net\mkharitonov\spectrum\core\SpecInterface[]
	 */
	private function getSpecs()
	{
		if ($this->getOwnerSpec() instanceof \net\mkharitonov\spectrum\core\SpecContainerInterface)
			return $this->getOwnerSpec()->getSpecs();
		else
			return array();
	}
}