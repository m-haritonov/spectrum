<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html;

use spectrum\config;
use spectrum\core\SpecInterface;

class html {
	static public function getContentBeforeSpec(SpecInterface $spec) {
		$content = '';
		
		if (!$spec->getParentSpecs()) {
			$content .= static::getHeader() . static::getHtmlEscapedOutputNewline();
			$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::callComponentMethod('totalInfo', 'getContent', array($spec))) . static::getHtmlEscapedOutputNewline();
		}

		$specListContent = static::callComponentMethod('specList', 'getContentBegin', array($spec));
		if ($specListContent != '') {
			$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline($specListContent) . static::getHtmlEscapedOutputNewline();
		}
		
		return $content;
	}

	static public function getContentAfterSpec(SpecInterface $spec) {
		$content = '';
		$specListContent = static::callComponentMethod('specList', 'getContentEnd', array($spec));
		if ($specListContent != '') {
			$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline($specListContent) . static::getHtmlEscapedOutputNewline();
		}
		
		if (!$spec->getParentSpecs()) {
			$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::callComponentMethod('totalInfo', 'getContent', array($spec))) . static::getHtmlEscapedOutputNewline();
			$content .= static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::callComponentMethod('totalInfo', 'getContentForUpdate', array($spec))) . static::getHtmlEscapedOutputNewline();
			$content .= static::getFooter();
		}
		
		return $content;
	}
	
	static protected function getHeader() {
		return
			static::getHtmlDeclaration() . static::getHtmlEscapedOutputNewline() .
			static::getHtmlOpenTag() . static::getHtmlEscapedOutputNewline() .
			'<head>' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<meta http-equiv="content-type" content="text/html; charset=' . static::escapeHtml(config::getOutputCharset()) . '" />' . static::getHtmlEscapedOutputNewline() .
				static::getHtmlEscapedOutputIndention() . '<title>' . static::translateAndEscapeHtml('Spectrum framework report') . '</title>' . static::getHtmlEscapedOutputNewline() .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getCommonStyles()) . static::getHtmlEscapedOutputNewline(2) .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::collectAllComponentStyles()) . static::getHtmlEscapedOutputNewline(2) .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::getCommonScripts()) . static::getHtmlEscapedOutputNewline(2) .
				static::prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline(static::collectAllComponentScripts()) . static::getHtmlEscapedOutputNewline() .
			'</head>' . static::getHtmlEscapedOutputNewline() .
			'<body><div>';
	}
	
	static protected function getHtmlDeclaration() {
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
	}

	static protected function getHtmlOpenTag() {
		return
			'<!--[if IE 6]><html class="app-browser-ie6" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->' . static::getHtmlEscapedOutputNewline() .
			'<!--[if IE 7]><html class="app-browser-ie7" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->' . static::getHtmlEscapedOutputNewline() .
			'<!--[if IE 8]><html class="app-browser-ie8" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->' . static::getHtmlEscapedOutputNewline() .
			'<!--[if IE 9]><html class="app-browser-ie9" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->' . static::getHtmlEscapedOutputNewline() .
			'<!--[if !IE]>--><html xmlns="http://www.w3.org/1999/xhtml"><!--<![endif]-->';
	}

	static protected function getFooter() {
		return '</div></body>' . static::getHtmlEscapedOutputNewline() . '</html>';
	}
	
	static protected function getCommonStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			html { background: #fff; }
			body { padding: 10px; font-family: Verdana, sans-serif; font-size: 0.75em; background: #fff; color: #000; }
			* { margin: 0; padding: 0; }
			*[title] { cursor: help; }
			a[title] { cursor: pointer; }
			.app-clearFix:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }
			html.app-browser-ie7 .app-clearFix { zoom: 1; }
		/*]]>*/</style>', 2);
	}

	static protected function getCommonScripts() {
		return static::formatTextForOutput('<script type="text/javascript">/*<![CDATA[*/
			spectrum = window.spectrum || {};
			spectrum.tools = {
				/**
				 * @param {HTMLElement} node
				 * @param {String} className
				 */
				hasClass: function(node, className) {
					return (node.className.match(new RegExp("(\\\\s|^)" + className + "(\\\\s|$)")) !== null);
				},
				
				/**
				 * @param {HTMLElement} node
				 * @param {String} classNamePrefix
				 */
				getClassesByPrefix: function(node, classNamePrefix) {
					var results = [];
					var classNames = node.className.split(/\\s+/);
					for (var i = 0; i < classNames.length; i++) {
						if (classNames[i].indexOf(classNamePrefix) === 0) {
							results.push(classNames[i]);
						}
					}
					
					return results;
				},

				/**
				 * @param {HTMLElement|NodeList|String} node
				 * @param {String} className
				 */
				addClass: function(node, className) {
					if (typeof(node) == "string") {
						node = document.querySelectorAll(node);
					}

					if (node instanceof (NodeList || StaticNodeList)) {
						for (var i = 0; i < node.length; i++) {
							arguments.callee(node[i], className);
						}
					} else if (!spectrum.tools.hasClass(node, className)) {
						node.className += " " + className;
					}
				},

				/**
				 * @param {HTMLElement|NodeList|String} node
				 * @param {String} className
				 */
				removeClass: function(node, className) {
					if (typeof(node) == "string") {
						node = document.querySelectorAll(node);
					}

					if (node instanceof (NodeList || StaticNodeList)) {
						for (var i = 0; i < node.length; i++) {
							arguments.callee(node[i], className);
						}
					}
					else if (spectrum.tools.hasClass(node, className)) {
						node.className = node.className.replace(new RegExp("(\\\\s|^)" + className + "(\\\\s|$)"), " ");
					}
				},
				
				getExecutingScriptNode: function() {
					var scripts = document.getElementsByTagName("script");
					return scripts[scripts.length - 1];
				},

				/**
				 * @param {HTMLElement} node
				 * @param {String} eventName
				 * @param {Function} callback
				 */
				addEventListener: function(node, eventName, callback) {
					var fixedCallback = function(event) {
						event = event || window.event;
						
						if (!event.isFixed) {
							event.isFixed = true;
							event.preventDefault = event.preventDefault || function(){ this.returnValue = false; };
							event.stopPropagation = event.stopPropagation || function(){ this.cancelBubble = true; };
							
							if (!event.target) {
								event.target = event.srcElement;
							}
							
							if (!event.relatedTarget && event.fromElement) {
								event.relatedTarget = event.fromElement == event.target ? event.toElement : event.fromElement;
							}
							
							if (event.pageX == null && event.clientX != null) {
								var html = document.documentElement;
								var body = document.body;
								event.pageX = event.clientX + (html && html.scrollLeft || body && body.scrollLeft || 0) - (html.clientLeft || 0);
								event.pageY = event.clientY + (html && html.scrollTop || body && body.scrollTop || 0) - (html.clientTop || 0);
							}
							
							if (!event.which && event.button) {
								event.which = (event.button & 1 ? 1 : (event.button & 2 ? 3 : (event.button & 4 ? 2 : 0)));
							}
						}
						
						return callback(event);
					};
				
					if (node.addEventListener) {
						node.addEventListener(eventName, fixedCallback, false);
					} else if (node.attachEvent) {
						node.attachEvent("on" + eventName, fixedCallback);
					}
				},

				/**
				 * @param {HTMLElement} node
				 * @param {String} eventName
				 */
				dispatchEvent: function(node, eventName) {
					if (document.createEvent) {
						var e = document.createEvent("HTMLEvents");
						e.initEvent(eventName, true, true);
						node.dispatchEvent(e);
					} else {
						node.fireEvent("on" + eventName, document.createEventObject());
					}
				}
			};
		/*]]>*/</script>', 2);
	}
	
	static protected function collectAllComponentStyles() {
		$content = '';
		foreach (config::getAllClassReplacements() as $class) {
			if (mb_stripos($class, '\spectrum\core\plugins\reports\drivers\html\components\\', null, 'us-ascii') === 0) {
				$styles = $class::getStyles();
				if ($styles != '') {
					$content .= $styles . static::getHtmlEscapedOutputNewline(2);
				}
			}
		}

		return $content;
	}

	static protected function collectAllComponentScripts() {
		$content = '';
		foreach (config::getAllClassReplacements() as $class) {
			if (mb_stripos($class, '\spectrum\core\plugins\reports\drivers\html\components\\', null, 'us-ascii') === 0) {
				$scripts = $class::getScripts();
				if ($scripts != '') {
					$content .= $scripts . static::getHtmlEscapedOutputNewline(2);
				}
			}
		}

		return $content;
	}
	
	static protected function callComponentMethod($componentShortName, $methodName, $arguments = array()) {
		return call_user_func_array(array(config::getClassReplacement('\spectrum\core\plugins\reports\drivers\html\components\\' . $componentShortName), $methodName), $arguments);
	}
	
	static protected function escapeHtml($html) {
		return htmlspecialchars($html, ENT_QUOTES, 'iso-8859-1');
	}

	static protected function getHtmlEscapedOutputIndention($repeat = 1) {
		return str_repeat(static::escapeHtml(config::getOutputIndention()), $repeat);
	}
	
	static protected function getHtmlEscapedOutputNewline($repeat = 1) {
		return str_repeat(static::escapeHtml(config::getOutputNewline()), $repeat);
	}
	
	static protected function prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline($text, $repeat = 1) {
		if ($text == '') {
			return $text;
		}
		
		$indention = static::getHtmlEscapedOutputIndention($repeat);
		$newline = static::getHtmlEscapedOutputNewline();
		return $indention . str_replace($newline, $newline . $indention, $text);
	}
	
	static protected function formatTextForOutput($text, $indentionToRemoveCount = 0) {
		$function = config::getFunctionReplacement('\spectrum\_internals\formatTextForOutput');
		return $function($text, $indentionToRemoveCount, "\t", "\n", static::escapeHtml(config::getOutputIndention()), static::escapeHtml(config::getOutputNewline()));
	}
	
	static protected function translateAndEscapeHtml($string, array $replacements = array()) {
		$translateFunction = config::getFunctionReplacement('\spectrum\_internals\translate');
		return static::escapeHtml($translateFunction($string, $replacements));
	}
	
	static protected function convertToOutputCharset($string, $inputCharset = null) {
		$function = config::getFunctionReplacement('\spectrum\_internals\convertCharset');
		return $function($string, $inputCharset);
	}
}