<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    class SendFriendForm extends Form {

        /*static public function primary_input_hidden($page) {
            parent::generic_input_hidden("id", $page["pk_i_id"]);
        }*/

        static public function your_name() {

            if( Session::newInstance()->_getForm("yourName") != "" ){
                $yourName = Session::newInstance()->_getForm("yourName");
                parent::generic_input_text("yourName", $yourName, null, false);
            } else {
                parent::generic_input_text("yourName", "", null, false);
            }
            return true;
        }

        static public function your_email() {

            if( Session::newInstance()->_getForm("yourEmail") != "" ){
                $yourEmail = Session::newInstance()->_getForm("yourEmail");
                parent::generic_input_text("yourEmail", $yourEmail, null, false);
            } else {
                parent::generic_input_text("yourEmail", "", null, false);
            }
            return true;
        }

        static public function friend_name() {
            if( Session::newInstance()->_getForm("friendName") != "" ){
                $friendName = Session::newInstance()->_getForm("friendName");
                parent::generic_input_text("friendName", $friendName, null, false);
            } else {
                parent::generic_input_text("friendName", "", null, false);
            }
            return true;
        }

        static public function friend_email() {
            if( Session::newInstance()->_getForm("friendEmail") != "" ){
                $friendEmail = Session::newInstance()->_getForm("friendEmail");
                parent::generic_input_text("friendEmail", $friendEmail, null, false);
            } else {
                parent::generic_input_text("friendEmail", "", null, false);
            }
            return true;
        }

        static public function your_message() {
            if( Session::newInstance()->_getForm("message_body") != "" ){
                $message_body = Session::newInstance()->_getForm("message_body");
                parent::generic_textarea("message", $message_body, null, false);
            } else {
                parent::generic_textarea("message", "");
            }
            return true;
        }

        static public function js_validation() {
?>
<script type="text/javascript">
    $(document).ready(function(){
        // Code for form validation
        $("form[name=sendfriend]").validate({
            rules: {
                yourName: {
                    required: true
                },
                yourEmail: {
                    required: true,
                    email: true
                },
                friendName: {
                    required: true
                },
                friendEmail: {
                    required: true,
                    email: true
                },
                message:  {
                    required: true
                }
            },
            messages: {
                yourName: {
                    required: "<?php _e("Your name: this field is required"); ?>."
                },
                yourEmail: {
                    email: "<?php _e("Invalid email address"); ?>.",
                    required: "<?php _e("Email: this field is required"); ?>."
                },
                friendName: {
                    required: "<?php _e("Friend's name: this field is required"); ?>."
                },
                friendEmail: {
                    required: "<?php _e("Friend's email: this field is required"); ?>.",
                    email: "<?php _e("Invalid friend's email address"); ?>."
                },
                message: "<?php _e("Message: this field is required"); ?>."

            },
            //onfocusout: function(element) { $(element).valid(); },
            errorLabelContainer: "#error_list",
            wrapper: "li",
            invalidHandler: function(form, validator) {
                $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
            },
            submitHandler: function(form){
                $('button[type=submit], input[type=submit]').attr('disabled', 'disabled');
                form.submit();
            }
        });
    });
</script>
<?php
        }

    }

?>