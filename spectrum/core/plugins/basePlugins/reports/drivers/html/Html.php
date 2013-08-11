<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html;

use spectrum\core\config;
use spectrum\core\plugins\basePlugins\reports\drivers\Driver;

class Html extends Driver
{
	protected $widgets = array(
		'Tools' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\Tools',
		'ClearFix' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\ClearFix',
		'TotalInfo' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\TotalInfo',
		'DetailsControl' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\DetailsControl',
		'totalResult\Result' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\totalResult\Result',
		'totalResult\Update' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\totalResult\Update',
		'Messages' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\Messages',
		'resultBuffer\ResultBuffer' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\resultBuffer\ResultBuffer',
		'resultBuffer\details\MatcherCall' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\resultBuffer\details\MatcherCall',
		'resultBuffer\details\VerifyCall' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\resultBuffer\details\VerifyCall',
		'resultBuffer\details\Unknown' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\resultBuffer\details\Unknown',
		'SpecList' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\SpecList',
		'SpecTitle' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\SpecTitle',
		'code\Method' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\Method',
		'code\Operator' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\Operator',
		'code\PhpSourceCode' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\PhpSourceCode',
		'code\Property' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\Property',
		'code\Variable' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\Variable',
		'code\variables\ArrayVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\variables\ArrayVar',
		'code\variables\BoolVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\variables\BoolVar',
		'code\variables\FloatVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\variables\FloatVar',
		'code\variables\FunctionVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\variables\FunctionVar',
		'code\variables\IntVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\variables\IntVar',
		'code\variables\NullVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\variables\NullVar',
		'code\variables\ObjectVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\variables\ObjectVar',
		'code\variables\ResourceVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\variables\ResourceVar',
		'code\variables\StringVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\variables\StringVar',
		'code\variables\UnknownVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\widgets\code\variables\UnknownVar',
	);
	
	public function getContentBeforeSpec()
	{
		$output = '';
		
		if ($this->getOwnerPlugin()->getOwnerSpec()->isRoot())
		{
			$output .= $this->getHeader();
			$output .= $this->createWidget('TotalInfo')->getHtml();
		}

		$output .= $this->createWidget('SpecList')->getHtmlBegin();
		
		$output .= str_repeat(' ', 256) . $this->getNewline();
		return $output;
	}

	public function getContentAfterSpec()
	{
		$output = '';
		
		$output .= $this->createWidget('SpecList')->getHtmlEnd();

		if ($this->getOwnerPlugin()->getOwnerSpec()->isRoot())
		{
			$totalInfoWidget = $this->createWidget('TotalInfo');
			$output .= $totalInfoWidget->getHtml();
			$output .= $totalInfoWidget->getHtmlForUpdate();
			$output .= $this->getFooter();
		}
		
		$output .= str_repeat(' ', 256) . $this->getNewline();
		return $output;
	}
	
	protected function getHeader()
	{
		return
			'<!DOCTYPE html>' . $this->getNewline() .
			'<html xmlns="http://www.w3.org/1999/xhtml">' . $this->getNewline() .
			'<head>' . $this->getNewline() .
				$this->getIndention() . '<meta http-equiv="content-type" content="text/html; charset=utf-8" />' . $this->getNewline() .
				$this->getIndention() . '<title></title>' . $this->getNewline() .
				$this->prependIndentionToEachLine($this->getStyles()) . $this->getNewline(2) .
				$this->prependIndentionToEachLine($this->getScripts()) . $this->getNewline() .
			'</head>' . $this->getNewline() .
			$this->getBodyTag();
	}

	protected function getBodyTag()
	{
		return
			'<!--[if IE 6]><body class="g-browser-ie g-browser-ie6"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 7]><body class="g-browser-ie g-browser-ie7"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 8]><body class="g-browser-ie g-browser-ie8"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 9]><body class="g-browser-ie g-browser-ie9"><![endif]-->' . $this->getNewline() .
			'<!--[if !IE]>--><body><!--<![endif]-->' . $this->getNewline();
	}

	protected function getStyles()
	{
		$output = '';
		$output .= $this->trimNewline($this->getCommonStyles()) . $this->getNewline(2);

		foreach ($this->createAllWidgets() as $widget)
			$output .= $this->trimNewline($widget->getStyles()) . $this->getNewline(2);

		return $output;
	}

	protected function getCommonStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . 'body { padding: 10px; font-family: Verdana, sans-serif; font-size: 0.75em; background: #fff; color: #000; }' . $this->getNewline() .
				$this->getIndention() . '* { margin: 0; padding: 0; }' . $this->getNewline() .
				$this->getIndention() . '*[title] { cursor: help; }' . $this->getNewline() .
				$this->getIndention() . 'a[title] { cursor: pointer; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}


	protected function getScripts()
	{
		$output = '';
		$output .= $this->trimNewline($this->getCommonScripts()) . $this->getNewline(2);

		foreach ($this->createAllWidgets() as $widget)
			$output .= $this->trimNewline($widget->getScripts()) . $this->getNewline(2);

		return $output;
	}

	protected function getCommonScripts()
	{
		return null;
	}
	
	protected function getFooter()
	{
		return '</body>' . $this->getNewline() . '</html>';
	}

/**/

	protected function createWidget($name/*, ... */)
	{
		$reflection = new \ReflectionClass($this->widgets[$name]);
		$args = func_get_args();
		array_shift($args);
		array_unshift($args, $this);

		return $reflection->newInstanceArgs($args);
	}
	
	protected function createAllWidgets()
	{
		$result = array();
		foreach ($this->widgets as $name => $class)
			$result[$name] = $this->createWidget($name);

		return $result;
	}
}