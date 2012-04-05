<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

class SpecContainerArgumentsProvider extends SpecContainer implements SpecContainerArgumentsProviderInterface
{
	public function addSpec(SpecInterface $spec)
	{
		// Arguments should be already sets to $spec
		if (!($spec instanceof SpecItemItInterface))
			throw new Exception('SpecContainerArgumentsProvider::addSpec() can accept only SpecItemItInterface instances');

		parent::addSpec($spec);
	}

	/**
	 * @param callback $testCallback
	 * @param array $argumentsProvider
	 */
	public function createSpecItemForEachArgumentsRow($testCallback, $argumentsProvider)
	{
		foreach ($this->correctArgumentsProvider($argumentsProvider) as $args)
		{
			$itClass = \spectrum\core\Config::getSpecItemItClass();
			$it = new $itClass();
			$it->setTestCallback($testCallback);
			$it->setAdditionalArguments($args);

			$this->addSpec($it);
		}
	}

	protected function correctArgumentsProvider(array $argumentsProvider)
	{
		foreach ($argumentsProvider as $key => $val)
		{
			if (!is_array($val))
			{
				$argumentsProvider[$key] = array($val);
			}
		}

		return $argumentsProvider;
	}

	public function getSpecsToRun()
	{
		return $this->getSpecs();
	}
}