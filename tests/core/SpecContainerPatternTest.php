<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;
require_once __DIR__ . '/../init.php';

class SpecContainerPatternTest extends SpecContainerTest
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerPattern';

	/**
	 * @var SpecContainerArgumentsProvider
	 */
	private $spec;
	protected function setUp()
	{
		parent::setUp();
		$this->spec = new SpecContainerPattern();
	}

/**/

	public function testSetArguments_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[0]->errorHandling->setCatchExceptions(false);
		$specs[1]->setTestCallback(function() use($specs){
			$specs[0]->setArguments('foo');
		});

		$this->assertThrowException('\spectrum\core\Exception', 'Modify specs when running deny', function() use($specs){
			$specs[0]->run();
		});
	}
}