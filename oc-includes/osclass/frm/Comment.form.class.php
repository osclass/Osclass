<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    class CommentForm extends Form 
    {

        static public function primary_input_hidden($comment = null) 
        {
            $commentId = null;
            if( isset($comment['pk_i_id']) ) {
                $commentId = $comment['pk_i_id'];
            }
            if(Session::newInstance()->_get('commentId') != '') {
                $commentId = Session::newInstance()->_get('commentId');
            }
            if( !is_null($commentId) ) {
                parent::generic_input_hidden("id", $commentId) ;
            }
        }

        static public function title_input_text($comment = null) 
        {
            $commentTitle = '';
            if( isset($comment['s_title']) ) {
                $commentTitle = $comment['s_title'];
            }
            if(Session::newInstance()->_get('commentTitle') != '') {
                $commentTitle = Session::newInstance()->_get('commentTitle');
            }
            parent::generic_input_text("title", $commentTitle, null, false) ;
        }

        static public function author_input_text($comment = null) 
        {
            $commentAuthorName = '';
            if( isset($comment['s_author_name']) ) {
                $commentAuthorName = $comment['s_author_name'];
            }
            if(Session::newInstance()->_get('commentAuthorName') != '') {
                $commentAuthorName = Session::newInstance()->_get('commentAuthorName');
            }
            parent::generic_input_text("authorName", $commentAuthorName, null, false) ;
        }

        static public function email_input_text($comment = null) 
        {
            $commentAuthorEmail = '';
            if( isset($comment['s_author_email']) ) {
                $commentAuthorEmail = $comment['s_author_email'];
            }
            if(Session::newInstance()->_get('commentAuthorEmail') != '') {
                $commentAuthorEmail = Session::newInstance()->_get('commentAuthorEmail');
            }
            parent::generic_input_text("authorEmail", $commentAuthorEmail, null, false) ;
        }

        static public function body_input_textarea($comment = null) 
        {
            $commentBody = '';
            if( isset($comment['s_body']) ) {
                $commentBody = $comment['s_body'];
            }
            if(Session::newInstance()->_get('commentBody') != '') {
                $commentBody = Session::newInstance()->_get('commentBody');
            }
            parent::generic_textarea("body", $commentBody);
        }

    }

?>