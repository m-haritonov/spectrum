<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\reports\drivers\html;

use spectrum\config;
use spectrum\core\plugins\basePlugins\reports\drivers\Driver;

class Html extends Driver
{
	protected $components = array(
		'Tools' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\Tools',
		'ClearFix' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\ClearFix',
		'TotalInfo' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\TotalInfo',
		'DetailsControl' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\DetailsControl',
		'totalResult\Result' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\totalResult\Result',
		'totalResult\Update' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\totalResult\Update',
		'Messages' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\Messages',
		'resultBuffer\ResultBuffer' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\resultBuffer\ResultBuffer',
		'resultBuffer\details\MatcherCall' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\resultBuffer\details\MatcherCall',
		'resultBuffer\details\Unknown' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\resultBuffer\details\Unknown',
		'SpecList' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\SpecList',
		'SpecTitle' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\SpecTitle',
		'code\Method' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\Method',
		'code\Operator' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\Operator',
		'code\Property' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\Property',
		'code\Variable' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\Variable',
		'code\variables\ArrayVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\ArrayVar',
		'code\variables\BoolVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\BoolVar',
		'code\variables\FloatVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\FloatVar',
		'code\variables\FunctionVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\FunctionVar',
		'code\variables\IntVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\IntVar',
		'code\variables\NullVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\NullVar',
		'code\variables\ObjectVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\ObjectVar',
		'code\variables\ResourceVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\ResourceVar',
		'code\variables\StringVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\StringVar',
		'code\variables\UnknownVar' => 'spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\UnknownVar',
	);
	
	public function getContentBeforeSpec()
	{
		$output = '';
		
		if (!$this->getOwnerPlugin()->getOwnerSpec()->getParentSpecs())
		{
			$output .= $this->getHeader();
			$output .= $this->createComponent('TotalInfo')->getHtml();
		}

		$output .= $this->createComponent('SpecList')->getHtmlBegin();
		
		$output .= str_repeat(' ', 256) . $this->getNewline();
		return $output;
	}

	public function getContentAfterSpec()
	{
		$output = '';
		
		$output .= $this->createComponent('SpecList')->getHtmlEnd();

		if (!$this->getOwnerPlugin()->getOwnerSpec()->getParentSpecs())
		{
			$totalInfoComponent = $this->createComponent('TotalInfo');
			$output .= $totalInfoComponent->getHtml();
			$output .= $totalInfoComponent->getHtmlForUpdate();
			$output .= $this->getFooter();
		}
		
		$output .= str_repeat(' ', 256) . $this->getNewline();
		return $output;
	}
	
	public function createComponent($name/*, ... */)
	{
		$reflection = new \ReflectionClass($this->components[$name]);
		$args = func_get_args();
		array_shift($args);
		array_unshift($args, $this);

		return $reflection->newInstanceArgs($args);
	}
	
	protected function createAllComponents()
	{
		$result = array();
		foreach ($this->components as $name => $class)
			$result[$name] = $this->createComponent($name);

		return $result;
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
			'<!--[if IE 6]><body class="c-browser-ie c-browser-ie6"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 7]><body class="c-browser-ie c-browser-ie7"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 8]><body class="c-browser-ie c-browser-ie8"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 9]><body class="c-browser-ie c-browser-ie9"><![endif]-->' . $this->getNewline() .
			'<!--[if !IE]>--><body><!--<![endif]-->' . $this->getNewline();
	}

	protected function getStyles()
	{
		$output = '';
		$output .= $this->trimNewline($this->getCommonStyles()) . $this->getNewline(2);

		foreach ($this->createAllComponents() as $component)
			$output .= $this->trimNewline($component->getStyles()) . $this->getNewline(2);

		return $output;
	}

	protected function getCommonStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . 'html { background: #fff; }' . $this->getNewline() .
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

		foreach ($this->createAllComponents() as $component)
			$output .= $this->trimNewline($component->getScripts()) . $this->getNewline(2);

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
}