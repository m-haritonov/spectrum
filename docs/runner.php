<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */


if ($_SERVER['SERVER_NAME'] != 'spectrum.local' || $_SERVER['SERVER_ADDR'] != '127.0.0.1')
	exit;

class Spaceship
{
	private $location = 'space';
	private $galaxy = '';
	private $planet = '';
	private $task = 'kill_people';

	public function getLocation()
	{
		return $this->location;
	}

	public function setLocation($location)
	{
		$this->location = $location;
	}

	public function startSystems(){}
	public function stopSystems(){}

	public function startEngine(){}
	public function stopEngine(){}

	public function setDestination($x, $y, $x){}

	public function isHasCollision()
	{
		return false;
	}

	public function setGalaxy($galaxy)
	{
		$this->galaxy = $galaxy;
	}

	public function getGalaxy()
	{
		return $this->galaxy;
	}

	public function setPlanet($planet)
	{
		$this->planet = $planet;
	}

	public function getPlanet()
	{
		return $this->planet;
	}

	public function setTask($foo)
	{
		$this->task = $foo;
	}

	public function getTask()
	{
		return $this->task;
	}
}

class Star
{
	public function __construct($x, $y, $x)
	{
	}
}

//
$init = dirname(__FILE__) . '/../spectrum/init.php';
require_once $init;

if (!isset($_REQUEST['enableDebug']))
	\spectrum\RootDescribe::getOnceInstance()->report->setOutputDebug(false);

$code = $_REQUEST['code'];
$code = preg_replace('/^\s*\<\?php/is', '', $code);
$code = str_replace('spectrum/spectrum/init.php', $init, $code);
eval($code);

if (!isset($_REQUEST['noRun']))
	\spectrum\RootDescribe::run();