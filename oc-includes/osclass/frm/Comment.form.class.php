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
            if(Session::newInstance()->_getForm('commentId') != '') {
                $commentId = Session::newInstance()->_getForm('commentId');
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
            if(Session::newInstance()->_getForm('commentTitle') != '') {
                $commentTitle = Session::newInstance()->_getForm('commentTitle');
            }
            parent::generic_input_text("title", $commentTitle, null, false) ;
        }

        static public function author_input_text($comment = null) 
        {
            $commentAuthorName = '';
            if( isset($comment['s_author_name']) ) {
                $commentAuthorName = $comment['s_author_name'];
            }
            if(Session::newInstance()->_getForm('commentAuthorName') != '') {
                $commentAuthorName = Session::newInstance()->_getForm('commentAuthorName');
            }
            parent::generic_input_text("authorName", $commentAuthorName, null, false) ;
        }

        static public function email_input_text($comment = null) 
        {
            $commentAuthorEmail = '';
            if( isset($comment['s_author_email']) ) {
                $commentAuthorEmail = $comment['s_author_email'];
            }
            if(Session::newInstance()->_getForm('commentAuthorEmail') != '') {
                $commentAuthorEmail = Session::newInstance()->_getForm('commentAuthorEmail');
            }
            parent::generic_input_text("authorEmail", $commentAuthorEmail, null, false) ;
        }

        static public function body_input_textarea($comment = null) 
        {
            $commentBody = '';
            if( isset($comment['s_body']) ) {
                $commentBody = $comment['s_body'];
            }
            if(Session::newInstance()->_getForm('commentBody') != '') {
                $commentBody = Session::newInstance()->_getForm('commentBody');
            }
            parent::generic_textarea("body", $commentBody);
        }

        static public function js_validation() {
?>
<script type="text/javascript">
    $(document).ready(function(){
        // Code for form validation
        $("form[name=comment_form]").validate({
            rules: {
                message: {
                    required: true,
                    minlength: 1
                },
                authorEmail: {
                    required: true,
                    email: true
                },
                authorName: {
                    required: true
                }
            },
            messages: {
                authorEmail: {
                    required: "<?php _e("Email: this field is required"); ?>.",
                    email: "<?php _e("Invalid email address"); ?>."
                },
                authorName: {
                    required: "<?php _e("Name: this field is required"); ?>."
                },
                message: {
                    required: "<?php _e("Message: this field is required"); ?>.",
                    minlength: "<?php _e("Message: this field is required"); ?>."
                }
            },
            errorLabelContainer: "#comment_error_list",
            wrapper: "li",
            invalidHandler: function(form, validator) {
                $('html,body').animate({ scrollTop: $('h2').offset().top }, { duration: 250, easing: 'swing'});
            }
        });
    });
</script>
<?php 
        }
        
        
    }

?>