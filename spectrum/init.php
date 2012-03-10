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

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
require_once dirname(__FILE__) . '/autoload.php';
\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin('reports', '\net\mkharitonov\spectrum\reports\Plugin', 'whenCallOnce');
require_once dirname(__FILE__) . '/constructionCommands/globalAliases.php';