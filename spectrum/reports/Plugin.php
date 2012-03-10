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
			return $this->getIndention($repeat) . str_replace($this->getNewline() . '<', $this->getNewline() . $this->getIndention($repeat) . '<', $text);
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

		$specListWidget = new \net\mkharitonov\spectrum\reports\widgets\SpecList($this);
		$this->getOwnerSpec()->output->put($specListWidget->getHtmlBegin());
		$this->flush();
	}

	public function onRunAfter($finalResult)
	{
		$specListWidget = new \net\mkharitonov\spectrum\reports\widgets\SpecList($this);
		$this->getOwnerSpec()->output->put($specListWidget->getHtmlEnd($finalResult));
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

		foreach ($this->getAllWidgets() as $widget)
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

		foreach ($this->getAllWidgets() as $widget)
			$output .= $this->trimNewline($widget->getScripts()) . $this->getNewline(2);

		return $output;
	}

	protected function getAllWidgets()
	{
		return array(
			new \net\mkharitonov\spectrum\reports\widgets\Clearfix($this),
			new \net\mkharitonov\spectrum\reports\widgets\Messages($this),
			new \net\mkharitonov\spectrum\reports\widgets\runResultsBuffer\RunResultsBuffer($this),
			new \net\mkharitonov\spectrum\reports\widgets\runResultsBuffer\details\MatcherCall($this),
			new \net\mkharitonov\spectrum\reports\widgets\runResultsBuffer\details\Unknown($this),
			new \net\mkharitonov\spectrum\reports\widgets\SpecList($this),
			new \net\mkharitonov\spectrum\reports\widgets\code\Code($this),
			new \net\mkharitonov\spectrum\reports\widgets\code\variables\ArrayVar($this),
			new \net\mkharitonov\spectrum\reports\widgets\code\variables\BoolVar($this),
			new \net\mkharitonov\spectrum\reports\widgets\code\variables\FloatVar($this),
			new \net\mkharitonov\spectrum\reports\widgets\code\variables\ClosureVar($this),
			new \net\mkharitonov\spectrum\reports\widgets\code\variables\IntVar($this),
			new \net\mkharitonov\spectrum\reports\widgets\code\variables\NullVar($this),
			new \net\mkharitonov\spectrum\reports\widgets\code\variables\ObjectVar($this),
			new \net\mkharitonov\spectrum\reports\widgets\code\variables\ResourceVar($this),
			new \net\mkharitonov\spectrum\reports\widgets\code\variables\StringVar($this),
			new \net\mkharitonov\spectrum\reports\widgets\code\variables\UnknownVar($this),
		);
	}

	protected function getCommonScripts()
	{
		return null;
	}

/**/

	protected function flush()
	{
		// TODO убрать span
		$this->getOwnerSpec()->output->put('<span style="display: none;">' . str_repeat(' ', 256) . '</span>' . $this->getNewline());
		flush();
	}
}