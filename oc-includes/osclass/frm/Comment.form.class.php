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

    class CommentForm extends Form {

        static public function primary_input_hidden($comment = null) {
            if(isset($comment['pk_i_id'])) {
                parent::generic_input_hidden("id", $comment["pk_i_id"]) ;
            }
        }

        static public function title_input_text($comment = null) {
            parent::generic_input_text("s_title", (isset($comment)) ? $comment["s_title"] : "", null, false) ;
            return true ;
        }

        static public function author_input_text($comment = null) {
            parent::generic_input_text("s_author_name", (isset($comment)) ? $comment["s_author_name"] : "", null, false) ;
            return true ;
        }

        static public function email_input_text($comment = null) {
            parent::generic_input_text("s_author_email", (isset($comment)) ? $comment["s_author_email"] : "", null, false) ;
            return true ;
        }

        static public function body_input_textarea($comment = null) {
            parent::generic_textarea("s_body", (isset($comment)) ? $comment["s_body"] : "");
            return true ;
        }

    }

?>