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

    class ContactForm extends Form {

        static public function primary_input_hidden() {
            parent::generic_input_hidden("id", osc_item_id() );
            return true;
        }

        static public function page_hidden() {
            parent::generic_input_hidden("page", 'item');
            return true;
        }

        static public function action_hidden() {
            parent::generic_input_hidden("action", 'contact_post');
            return true;
        }

        static public function your_name() {
            if( Session::newInstance()->_getForm("yourName") != "" ) {
                $name = Session::newInstance()->_getForm("yourName");
                parent::generic_input_text("yourName", $name, null, false);
            } else {
                parent::generic_input_text("yourName", osc_logged_user_name(), null, false);
            }
            return true;
        }

        static public function your_email() {
             if( Session::newInstance()->_getForm("yourEmail") != "" ) {
                $email = Session::newInstance()->_getForm("yourEmail");
                parent::generic_input_text("yourEmail", $email, null, false);
            } else {
                parent::generic_input_text("yourEmail", osc_logged_user_email(), null, false);
            }
            return true;
        }

        static public function your_phone_number() {
            if( Session::newInstance()->_getForm("phoneNumber") != "" ) {
                $phoneNumber = Session::newInstance()->_getForm("phoneNumber");
                parent::generic_input_text("phoneNumber", $phoneNumber, null, false);
            } else {
                parent::generic_input_text("phoneNumber", osc_logged_user_phone(), null, false);
            }
            return true;
        }

        static public function the_subject() {
            if( Session::newInstance()->_getForm("subject") != "" ) {
                $subject = Session::newInstance()->_getForm("subject");
                parent::generic_input_text("subject", $subject, null, false);
            } else {
                parent::generic_input_text("subject", "", null, false);
            }
            return true;
        }

        static public function your_message() {
            if( Session::newInstance()->_getForm("message_body") != "" ) {
                $message = Session::newInstance()->_getForm("message_body");
                parent::generic_textarea("message", $message);
            } else {
                parent::generic_textarea("message", "");
            }
            return true;
        }

        static public function your_attachment() {
            echo '<input type="file" name="attachment" />';
        }

        static public function js_validation() {
?>
<script type="text/javascript">
    $(document).ready(function(){
        // Code for form validation
        $("form[name=contact_form]").validate({
            rules: {
                message: {
                    required: true,
                    minlength: 1
                },
                yourEmail: {
                    required: true,
                    email: true
                }
            },
            messages: {
                yourEmail: {
                    required: "<?php _e("Email: this field is required"); ?>.",
                    email: "<?php _e("Invalid email address"); ?>."
                },
                message: {
                    required: "<?php _e("Message: this field is required"); ?>.",
                    minlength: "<?php _e("Message: this field is required"); ?>."
                }
            },
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