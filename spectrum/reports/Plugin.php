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

namespace net\mkharitonov\spectrum\reports;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Plugin extends \net\mkharitonov\spectrum\core\plugins\Plugin implements \net\mkharitonov\spectrum\core\plugins\events\OnRunInterface
{
	protected $indention = "\t";
	protected $newline = "\r\n";
	protected $widgets = array(
		'clearfix'         => 'net\mkharitonov\spectrum\reports\widgets\Clearfix',
		'finalResult'      => 'net\mkharitonov\spectrum\reports\widgets\FinalResult',
		'messages'         => 'net\mkharitonov\spectrum\reports\widgets\Messages',
		'runResultsBuffer' => 'net\mkharitonov\spectrum\reports\widgets\runResultsBuffer\RunResultsBuffer',
		'matcherCallDetails' => 'net\mkharitonov\spectrum\reports\widgets\runResultsBuffer\details\MatcherCall',
		'unknownDetails'     => 'net\mkharitonov\spectrum\reports\widgets\runResultsBuffer\details\Unknown',
		'specList'         => 'net\mkharitonov\spectrum\reports\widgets\SpecList',
		'specTitle'        => 'net\mkharitonov\spectrum\reports\widgets\SpecTitle',
		'code'             => 'net\mkharitonov\spectrum\reports\widgets\code\Code',
		'arrayVar'         => 'net\mkharitonov\spectrum\reports\widgets\code\variables\ArrayVar',
		'boolVar'          => 'net\mkharitonov\spectrum\reports\widgets\code\variables\BoolVar',
		'floatVar'         => 'net\mkharitonov\spectrum\reports\widgets\code\variables\FloatVar',
		'closureVar'       => 'net\mkharitonov\spectrum\reports\widgets\code\variables\ClosureVar',
		'intVar'           => 'net\mkharitonov\spectrum\reports\widgets\code\variables\IntVar',
		'nullVar'          => 'net\mkharitonov\spectrum\reports\widgets\code\variables\NullVar',
		'objectVar'        => 'net\mkharitonov\spectrum\reports\widgets\code\variables\ObjectVar',
		'resourceVar'      => 'net\mkharitonov\spectrum\reports\widgets\code\variables\ResourceVar',
		'stringVar'        => 'net\mkharitonov\spectrum\reports\widgets\code\variables\StringVar',
		'unknownVar'       => 'net\mkharitonov\spectrum\reports\widgets\code\variables\UnknownVar',
	);

/**/

	public function translate($string)
	{
		return $string;
	}

	public function createWidget($name/*, ... */)
	{
		$reflection = new \ReflectionClass($this->widgets[$name]);
		$args = func_get_args();
		array_shift($args);
		array_unshift($args, $this);

		return $reflection->newInstanceArgs($args);
	}

/**/

	public function setIndention($string)
	{
		if (!Config::getAllowPluginSettingsModify())
			throw new Exception('Reports settings modify deny in Config');

		$this->indention = $string;
	}

	public function getIndention($repeat = 1)
	{
		return str_repeat($this->indention, $repeat);
	}

	public function prependIndentionToEachTagOnNewline($text, $repeat = 1)
	{
		if ($text != '')
			return preg_replace('/' . preg_quote($this->getNewline(), '/') . '(<[^\/])/s', $this->getNewline() . $this->getIndention($repeat) . '$1', $text);
		else
			return $text;
	}

/**/

	public function setNewline($newline)
	{
		if (!Config::getAllowPluginSettingsModify())
			throw new Exception('Reports settings modify deny in Config');

		$this->newline = $newline;
	}

	public function getNewline($repeat = 1)
	{
		return str_repeat($this->newline, $repeat);
	}

	public function trimNewline($text)
	{
		$escapedNewline = preg_quote($this->newline, '/');
		return preg_replace('/^(' . $escapedNewline . ')+|(' . $escapedNewline . ')+$/s', '', $text);
	}

/**/

	public function onRunBefore()
	{
		if (!$this->getOwnerSpec()->getParent())
			$this->getOwnerSpec()->output->put($this->getHeader());

		$this->getOwnerSpec()->output->put($this->createWidget('specList')->getHtmlBegin());
		$this->flush();
	}

	public function onRunAfter($finalResult)
	{
		$this->getOwnerSpec()->output->put($this->createWidget('specList')->getHtmlEnd($finalResult));
		$this->flush();

		if (!$this->getOwnerSpec()->getParent())
			$this->getOwnerSpec()->output->put($this->getFooter());
	}

/**/

	protected function getHeader()
	{
		return
			'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . $this->getNewline() .
			'<html xmlns="http://www.w3.org/1999/xhtml">' . $this->getNewline() .
			'<head>' . $this->getNewline() .
				$this->getIndention() . '<meta http-equiv="content-type" content="text/html; charset=utf-8" />' . $this->getNewline() .
				$this->getIndention() . '<title></title>' . $this->getNewline() .
				$this->prependIndentionToEachTagOnNewline($this->trimNewline($this->getStyles())) . $this->getNewline(2) .
				$this->prependIndentionToEachTagOnNewline($this->trimNewline($this->getScripts())) . $this->getNewline() .
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

	protected function getFooter()
	{
		return '</body>' . $this->getNewline() . '</html>';
	}

/**/

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
				$this->getIndention() . 'body { padding: 10px; font-family: Verdana, sans-serif; font-size: 0.75em; }' . $this->getNewline() .
				$this->getIndention() . '* { margin: 0; padding: 0; }' . $this->getNewline() .
				$this->getIndention() . '*[title] { cursor: help; }' . $this->getNewline() .
				$this->getIndention() . 'a[title] { cursor: auto; }' . $this->getNewline() .
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

/**/


	protected function createAllWidgets()
	{
		$result = array();
		foreach ($this->widgets as $name => $class)
			$result[$name] = $this->createWidget($name);

		return $result;
	}

	protected function flush()
	{
		// TODO убрать span
		$this->getOwnerSpec()->output->put('<span style="display: none;">' . str_repeat(' ', 256) . '</span>' . $this->getNewline());
		flush();
	}
}