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
					hasClass: function(node, className)
					{
						return (node.className.match(new RegExp("(\\\\s|^)" + className + "(\\\\s|$)")) !== null);
					},

					addClass: function(node, className)
					{
						if (!tools.hasClass(node, className))
							node.className += " " + className;
					},

					removeClass: function(node, className)
					{
						if (tools.hasClass(node, className))
							node.className = node.className.replace(new RegExp("(\\\\s|^)" + className + "(\\\\s|$)"), " ");
					}
				};' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}
}