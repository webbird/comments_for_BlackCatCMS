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

$debug = false;
require dirname(__FILE__).'/../init.php';
$val   = CAT_Helper_Validate::getInstance();

$form_submitted = $val->sanitizePost('submit');
$errors         = NULL;
if($form_submitted) {
    global $section_id;
    $section_id = $val->sanitizePost('section_id');
    $errors = comments::saveComment();
}

if(is_array($errors) && count($errors)) $success = false;
else                                    $success = true;

$val->lang()->addFile(LANGUAGE.'.php',dirname(__FILE__).'/../languages');

header('Content-type: application/json');
echo CAT_Object::json_result(
    $success,
    (
        $success
        ? $val->lang()->translate('Your comment was saved!') . ( comments::$moderated ? '<br />'.$val->lang()->translate('Comments are moderated, so the administrator will have to accept it before it is shown here.') : '' )
        : $val->lang()->translate('An error occured when trying to save your comment. Please contact the administrator.') . ( $debug ? ' '.print_r($errors,1) : '' )
    )
);
