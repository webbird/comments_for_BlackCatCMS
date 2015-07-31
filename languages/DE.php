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

$LANG = array(
    'An error occured when trying to save your comment. Please contact the administrator.' => 'Beim Speichern Ihres Kommentars ist leider ein Fehler aufgetreten. Bitte kontaktieren Sie den Administrator.',
    'At least {0} characters required!' => 'Mindestens {0} Zeichen erforderlich!',
    'Comments' => 'Kommentare',
    'Comments are moderated, so the administrator will have to accept it before it is shown here.' => 'Kommentare werden moderiert, das heißt der Administrator muss ihn erst freischalten bevor er hier zu sehen ist.',
    'Content' => 'Inhalt',
    'Join the discussion' => 'Kommentar schreiben',
    'Leave a Comment' => 'Kommentar hinterlassen',
    'Please leave a comment' => 'Bitte einen Kommentar angeben',
    'Please specify your name' => 'Bitte Namen angeben',
    'Reply' => 'Antworten',
    'Submit comment' => 'Kommentar absenden',
    'This comment and all it\'s replies will be deleted! This action cannot be recovered. Are you sure that you want to do this?' => 'Dieser Kommentar und alle Antworten darauf werden gelöscht! Diese Aktion kann nicht rückgängig gemacht werden. Sind Sie sicher, dass Sie das tun wollen?',
    'Your comment was saved!' => 'Ihr Kommentar wurde gespeichert!',
    'Your homepage (optional)' => 'Homepage (optional)',
    'Your name' => 'Name',
    'Your email' => 'eMail Adresse',
    'Your email address is required' => 'Die Mailadresse ist ein Pflichtfeld',
    'Your email address must be in the format of name@domain.com' => 'Die Mailadresse muss das Format name@domain.com haben',
    'Your message' => 'Kommentar',
);