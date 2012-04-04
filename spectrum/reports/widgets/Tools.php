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

namespace net\mkharitonov\spectrum\reports\widgets;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Tools extends \net\mkharitonov\spectrum\reports\widgets\Widget
{
	public function getScripts()
	{
		return
			'<script type="text/javascript">

				tools = {
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
						else if (!tools.hasClass(node, className))
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
						else if (tools.hasClass(node, className))
							node.className = node.className.replace(new RegExp("(\\\\s|^)" + className + "(\\\\s|$)"), " ");
					}
				};' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}
}