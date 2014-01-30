<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\reports\drivers\html;

use spectrum\config;
use spectrum\core\plugins\basePlugins\reports\drivers\Driver;

class Html extends Driver
{
	protected $components = array(
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
			$this->getHtmlTag() .
			'<head>' . $this->getNewline() .
				$this->getIndention() . '<meta http-equiv="content-type" content="text/html; charset=' . config::getOutputCharset() . '" />' . $this->getNewline() .
				$this->getIndention() . '<title></title>' . $this->getNewline() .
				$this->prependIndentionToEachLine($this->getStyles()) . $this->getNewline(2) .
				$this->prependIndentionToEachLine($this->getScripts()) . $this->getNewline() .
			'</head>' . $this->getNewline() .
			'<body>' . $this->getNewline();
	}

	protected function getHtmlTag()
	{
		return
			'<!--[if IE 6]><html class="c-browser-ie6"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 7]><html class="c-browser-ie7"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 8]><html class="c-browser-ie8"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 9]><html class="c-browser-ie9"><![endif]-->' . $this->getNewline() .
			'<!--[if !IE]>--><html><!--<![endif]-->' . $this->getNewline();
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
		return
			'<script type="text/javascript">
				spectrum = window.spectrum || {};
				spectrum.tools = {
					/**
					 * @param {HTMLElement} node
					 */
					hasClass: function(node, className)
					{
						return (node.className.match(new RegExp("(\\\\s|^)" + className + "(\\\\s|$)")) !== null);
					},

					/**
					 * @param {HTMLElement|NodeList|String} node
					 */
					addClass: function(node, className)
					{
						if (typeof(node) == "string")
							node = document.querySelectorAll(node);

						if (node instanceof (NodeList || StaticNodeList))
						{
							for (var i = 0; i < node.length; i++)
								arguments.callee(node[i], className);
						}
						else if (!spectrum.tools.hasClass(node, className))
							node.className += " " + className;
					},

					/**
					 * @param {HTMLElement|NodeList|String} node
					 */
					removeClass: function(node, className)
					{
						if (typeof(node) == "string")
							node = document.querySelectorAll(node);

						if (node instanceof (NodeList || StaticNodeList))
						{
							for (var i = 0; i < node.length; i++)
								arguments.callee(node[i], className);
						}
						else if (spectrum.tools.hasClass(node, className))
							node.className = node.className.replace(new RegExp("(\\\\s|^)" + className + "(\\\\s|$)"), " ");
					},
					
					getExecuteScriptNode: function()
					{
						var scripts = document.getElementsByTagName("script");
						return scripts[scripts.length - 1];
					},

					/**
					 * @param {HTMLElement} node
					 */
					addEventListener: function(node, eventName, callback)
					{
						var fixedCallback = function(event)
						{
							event = event || window.event;
							
							if (!event.isFixed)
							{
								event.isFixed = true 
								
								event.preventDefault = event.preventDefault || function(){ this.returnValue = false; };
								event.stopPropagation = event.stopPropagation || function(){ this.cancelBubble = true; };
								
								if (!event.target)
									event.target = event.srcElement;
								
								if (!event.relatedTarget && event.fromElement)
									event.relatedTarget = event.fromElement == event.target ? event.toElement : event.fromElement;
								
								if (event.pageX == null && event.clientX != null)
								{
									var html = document.documentElement
									var body = document.body;
									event.pageX = event.clientX + (html && html.scrollLeft || body && body.scrollLeft || 0) - (html.clientLeft || 0);
									event.pageY = event.clientY + (html && html.scrollTop || body && body.scrollTop || 0) - (html.clientTop || 0);
								}
								
								if (!event.which && event.button)
									event.which = (event.button & 1 ? 1 : (event.button & 2 ? 3 : (event.button & 4 ? 2 : 0)));
							}
							
							return callback(event);
						}
					
						if (node.addEventListener)
							node.addEventListener(eventName, fixedCallback, false);
						else if (node.attachEvent)
							node.attachEvent("on" + eventName, fixedCallback);
					},

					/**
					 * @param {HTMLElement} node
					 */
					dispatchEvent: function(node, eventName)
					{
						if (document.createEvent)
						{
							var e = document.createEvent("HTMLEvents");
							e.initEvent(eventName, true, true);
							node.dispatchEvent(e);
						}
						else
							node.fireEvent("on" + eventName, document.createEventObject());
					}
				};' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}
	
	protected function getFooter()
	{
		return '</body>' . $this->getNewline() . '</html>';
	}
}