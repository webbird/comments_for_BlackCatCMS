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

if (!class_exists('comments'))
{
    class comments
    {

        public static $name_min_length = 3;
        public static $comment_min_length = 15;
        public static $moderated = true;
        public static $use_captcha = true;


        /**
         *
         * @access public
         * @return
         **/
        public static function delComment($id)
        {
            global $section_id;
            $db    = CAT_Helper_DB::getInstance();
            $todel = array($id);

            // check if the comment has children; they will be deleted, too
            $children = self::getChildren($id);
            if(count($children))
            {
                array_reverse($children);
                $parent = array_shift($children[$id]);
                while($parent)
                {
                    array_unshift($todel,$parent);
                    if(isset($children[$parent]))
                        $parent = array_shift($children[$parent]);
                    else
                        $parent = NULL;
                }
            }
            foreach($todel as $item)
            {
                $result = $db->query(
                    'DELETE FROM `:prefix:mod_comments` WHERE `section_id`=? && `id`=?',
                    array($section_id,$item)
                );
                if($db->isError()) return false;
            }
            return true;
        }   // end function delComment()
        

        /**
         *
         * @access public
         * @return
         **/
        public static function getSettings()
        {
        
        }   // end function getSettings()
        
        /**
         *
         * @access public
         * @return
         **/
        public static function getChildren($id)
        {
            $comments = self::getComments();
            $comment  = array();
            foreach($comments as $c)
            {
                if($c['id'] == $id)
                {
                    $comment = $c;
                    break;
                }
            }
            if(isset($comment['__children']) && $comment['__children'] > 0)
            {
                $children = array();
                foreach($comments as $c)
                {
                    $children[$c['parent']][] = $c['id'];
                }
                return $children;
            }
        }   // end function getChildren()
        
        /**
         *
         * @access getComments
         * @return
         **/
        public static function getComments()
        {
            global $section_id;
            $cond = ( !CAT_Backend::isBackend() ? ' AND `moderation_pending`=?' : '' );
            $params = array($section_id);
            if(!CAT_Backend::isBackend()) $params[] = 'N';
            $comments = CAT_Helper_DB::getInstance()->query(
                'SELECT * FROM `:prefix:mod_comments` WHERE `section_id`=? '.$cond.' ORDER BY `id`',
                $params
            )->fetchAll();
            if(count($comments))
            {
                foreach($comments as &$c)
                {
                    $c['email_md5'] = md5(strtolower(trim($c['email'])));
                }
            }
            return self::sortComments($comments,0);
        }   // end function ()

        /**
         *
         * @access public
         * @return
         **/
        public static function saveComment()
        {
            global $section_id;

            $val     = CAT_Helper_Validate::getInstance();

            // check for valid section id
            if(!$section_id || !is_numeric($section_id))
                return array('global'=>'invalid section_id');

            // first: check captcha
            if(self::$use_captcha && !CAT_Helper_Captcha::check())
                return array('captcha'=>$val->lang()->translate('Invalid captcha'));

            // validate
            $comment = $val->sanitizePost('comment');
            $author  = $val->sanitizePost('comment_author');
            $email   = $val->sanitizePost('email');
            $hp      = $val->sanitizePost('homepage');
            $parent  = $val->sanitizePost('parent');
            $level   = 0;
            $errors  = array();

            if(strlen($comment) < self::$comment_min_length)
                $errors['comment'] = $val->lang()->translate(str_replace('{0}',self::$comment_min_length,'At least {0} characters required!'));
            if(strlen($author) < self::$name_min_length)
                $errors['comment_author'] = $val->lang()->translate(str_replace('{0}',self::$name_min_length,'At least {0} characters required!'));
            if(!$val->sanitize_email($email))
                $errors['email'] = $val->lang()->translate('Your email address must be in the format of name@domain.com');
            if(count($errors))
                return $errors;

            if($parent == '#' || !is_numeric($parent))
                $parent = 0;

            if($parent)
            {
                $r = CAT_Helper_DB::getInstance()->query(
                    'SELECT `level` FROM `:prefix:mod_comments` WHERE `id`=?',
                    array($parent)
                );
                if(is_object($r))
                {
                    $result = $r->fetch();
                    $level  = $result['level'] + 1;
                }
            }

            CAT_Helper_DB::getInstance()->query(
                'INSERT INTO `:prefix:mod_comments` '.
                '( `section_id`, `parent`, `level`, `name`, `email`, `homepage`, `content`, `moderation_pending` ) '.
                'VALUES ( ?,?,?,?,?,?,?,? )',
                array(
                    $section_id,
                    $parent,
                    $level,
                    $author,
                    $email,
                    $hp,
                    $comment,
                    self::$moderated
                )
            );

            if(!CAT_Helper_DB::getInstance()->isError())
                return true;
            else
                return array('db'=>CAT_Helper_DB::getInstance()->getError());
        }   // end function saveComment()
        

        /**
         * sort comments by parent
         *
         * @access public
         * @param  array  $comments
         * @return array
         **/
        public static function sortComments($list,$root_id)
        {
            if ( empty($list) || ! is_array( $list ) || count($list) == 0 )
            {
                return NULL;
            }

            // if there's only one item, nothing to do
            if ( ! ( count($list) > 1 ) )
            {
                return $list;
            }

            $return    = array();
            $children  = array();
            $p_key     = 'parent';
            $id_key    = 'id';

            // create a list of children for each item
            foreach ( $list as $item )
            {
                $children[$item[$p_key]][] = $item;
            }

            // loop will be false if the root has no children
            $loop         = !empty( $children[$root_id] );

            // initializing $parent as the root
            $parent       = $root_id;
            $parent_stack = array();

            while ( $loop && ( ( $option = each( $children[$parent] ) ) || ( $parent > $root_id ) ) )
            {
                if ( $option === false ) // no more children
                {
                    $parent = array_pop( $parent_stack );
                }
                // current item has children
                elseif ( ! empty( $children[$option['value'][$id_key]] ) )
                {
                    if(!isset($option['value']['__children']))
                        $option['value']['__children'] = count($children[ $option['value'][$id_key] ]);
                    $return[] = $option['value'];
                    array_push( $parent_stack, $option['value'][$p_key] );
                    $parent = $option['value'][$id_key];
                }
                else {
                    if(!isset($option['value']['__children']))
                        $option['value']['__children'] = 0;
                    $return[] = $option['value'];
                }
            }

            return $return;
        }   // end function sortComments()
        
        
    } // class comments
} // if class_exists()