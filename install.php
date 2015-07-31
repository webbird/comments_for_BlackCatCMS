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

$db = CAT_Helper_DB::getInstance();
$t  = TABLE_PREFIX.'mod_comments';

$db->query(
    "CREATE TABLE IF NOT EXISTS `$t` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `section_id` int(11) unsigned NOT NULL,
      `parent` int(11) unsigned DEFAULT NULL,
      `level` int(5) unsigned NOT NULL,
      `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `name` varchar(150) NOT NULL,
      `email` varchar(150) NOT NULL,
      `homepage` varchar(150) DEFAULT NULL,
      `content` text NOT NULL,
      `moderation_pending` enum('Y','N') NOT NULL DEFAULT 'N',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
);


// add files to class_secure
$addons_helper = new CAT_Helper_Addons();
foreach(
	array(
		'ajax/ajax_save.php'
	)
	as $file
) {
	if ( false === $addons_helper->sec_register_file( 'comments', $file ) )
	{
		 error_log( "Unable to register file -$file-!" );
    }
}
