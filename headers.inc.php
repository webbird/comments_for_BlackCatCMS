<?php

/**
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 3 of the License, or (at
 *   your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful, but
 *   WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 *   General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, see <http://www.gnu.org/licenses/>.
 *
 *   @author          BlackBird Webprogrammierung
 *   @copyright       2015, BlackBird Webprogrammierung
 *   @link            http://blackcat-cms.org
 *   @license         http://www.gnu.org/licenses/gpl.html
 *   @category        CAT_Modules
 *   @package         Comments
 *
 */

if (defined('CAT_PATH')) {
    if (defined('CAT_VERSION')) include(CAT_PATH.'/framework/class.secure.php');
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
    include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php');
} else {
    $subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));    $dir = $_SERVER['DOCUMENT_ROOT'];
    $inc = false;
    foreach ($subs as $sub) {
        if (empty($sub)) continue; $dir .= '/'.$sub;
        if (file_exists($dir.'/framework/class.secure.php')) {
            include($dir.'/framework/class.secure.php'); $inc = true;    break;
        }
    }
    if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}


$mod_headers = array(
    'backend'    => array(
        'js'     => array(),
        'css'    => array(),
        'jquery' => array( 'core' => true, 'ui' => true ),
        'meta'   => array(),
    ),
    'frontend' => array(
        'js'  => array('/modules/lib_jquery/plugins/cattranslate/cattranslate.js'),
        'css' => array(),
    ),
);

if(file_exists(CAT_PATH.'/modules/lib_jquery/plugins/jquery.validation/jquery.validate.min.js'))
{
    $mod_headers['frontend']['js'][] = '/modules/lib_jquery/plugins/jquery.validation/jquery.validate.min.js';
}
if(file_exists(CAT_PATH.'/modules/lib_jquery/plugins/qtip2/qtip2.min.js'))
{
    $mod_headers['frontend']['js'][] = '/modules/lib_jquery/plugins/qtip2/qtip2.min.js';
    $mod_headers['frontend']['css'][] = array('file'=>'/modules/lib_jquery/plugins/qtip2/qtip2.min.css');
}