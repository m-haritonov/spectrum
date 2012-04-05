<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

require_once dirname(__FILE__) . '/autoload.php';
\spectrum\core\plugins\Manager::registerPlugin('reports', '\spectrum\reports\Plugin', 'whenCallOnce');
require_once dirname(__FILE__) . '/constructionCommands/globalAliases.php';