<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;
require_once dirname(__FILE__) . '/../init.php';

class RegistryTest extends Test
{
	public function testGetRunningSpecItem_ShouldBeReturnNullByDefault()
	{
		$this->assertNull(Registry::getRunningSpecItem());
	}

/**/

	public function testSetRunningSpecItem_ShouldBeSetNewInstance()
	{
		$it = new SpecItemIt();
		$reflection = new \ReflectionProperty($it, 'isRunning');
		$reflection->setAccessible(true);
		$reflection->setValue($it, true);

		Registry::setRunningSpecItem($it);
		$this->assertSame($it, Registry::getRunningSpecItem());
	}

	public function testSetRunningSpecItem_ShouldBeThrowExceptionIfSpecItemNotRunning()
	{
		$this->assertThrowException('\spectrum\core\Exception', 'only running specs', function(){
			Registry::setRunningSpecItem(new SpecItemIt());
		});
	}

	public function testSetRunningSpecItem_ShouldBeAcceptNull()
	{
		Registry::setRunningSpecItem(null);
		$this->assertNull(Registry::getRunningSpecItem());
	}
}