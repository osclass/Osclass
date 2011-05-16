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

    class SendFriendForm extends Form {

        /*static public function primary_input_hidden($page) {
            parent::generic_input_hidden("id", $page["pk_i_id"]) ;
        }*/

        static public function your_name() {
            
            if( Session::newInstance()->_get("yourName") != "" ){
                $yourName = Session::newInstance()->_get("yourName");
                parent::generic_input_text("yourName", $yourName, null, false);
                Session::newInstance()->_drop("yourName");
            } else {
                parent::generic_input_text("yourName", "", null, false);
            }
            return true ;
        }

        static public function your_email() {
            
            if( Session::newInstance()->_get("yourEmail") != "" ){
                $yourEmail = Session::newInstance()->_get("yourEmail");
                parent::generic_input_text("yourEmail", $yourEmail, null, false);
                Session::newInstance()->_drop("yourEmail");
            } else {
                parent::generic_input_text("yourEmail", "", null, false);
            }
            return true ;
        }

        static public function friend_name() {
            if( Session::newInstance()->_get("friendName") != "" ){
                $friendName = Session::newInstance()->_get("friendName");
                parent::generic_input_text("friendName", $friendName, null, false);
                Session::newInstance()->_drop("friendName");
            } else {
                parent::generic_input_text("friendName", "", null, false);
            }
            return true ;
        }

        static public function friend_email() {
            if( Session::newInstance()->_get("friendEmail") != "" ){
                $friendEmail = Session::newInstance()->_get("friendEmail");
                parent::generic_input_text("friendEmail", $friendEmail, null, false);
                Session::newInstance()->_drop("friendEmail");
            } else {
                parent::generic_input_text("friendEmail", "", null, false);
            }
            return true ;
        }

        static public function your_message() {
            if( Session::newInstance()->_get("message_body") != "" ){
                $message_body = Session::newInstance()->_get("message_body");
                parent::generic_textarea("message", $message_body, null, false);
                Session::newInstance()->_drop("message_body");
            } else {
                parent::generic_textarea("message", "");
            }
            return true ;
        }

        static public function js_validation() { ?>
<script type="text/javascript">
    function validate_form() {
        email = $("#yourEmail");
        friendemail = $("#friendEmail");
        yourname = $("#yourName");
        friendname = $("#friendName");
        message = $("#message");

        var pattern=/^([a-zA-Z0-9_\.-])+@([a-zA-Z0-9_\.-]+)\.([a-zA-Z]{2,3})$/;
        var num_error = 0;

        if(!pattern.test(email.val())){
            email.css('border', '1px solid red');
            num_error = num_error + 1;
        }

        if(!pattern.test(friendemail.val())){
            friendemail.css('border', '1px solid red');
            num_error = num_error + 1;
        }

        if(yourname.val().length<=0){
            yourname.css('border', '1px solid red');
            num_error = num_error + 1;
        }

        if(friendname.val().length<=0){
            friendname.css('border', '1px solid red');
            num_error = num_error + 1;
        }

        if(message.val().length < 1) {
            message.css('border', '1px solid red');
            num_error = num_error + 1;
        }

        if(num_error > 0) {
            return false;
        } else {
                        document.forms['send-friend'].submit();
            return true;
                }
    }

    $(document).ready(function(){
        $("#yourEmail").focus(function(){
            $(this).css('border', '');
        });
        $("#friendEmail").focus(function(){
            $(this).css('border', '');
        });
        $("#yourName").focus(function(){
            $(this).css('border', '');
        });
        $("#friendName").focus(function(){
            $(this).css('border', '');
        });
        $("#message").focus(function(){
            $(this).css('border', '');
        });
    });
</script>
        <?php }


    }

?>