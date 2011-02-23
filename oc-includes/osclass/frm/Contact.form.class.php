<?php
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

class ContactForm extends Form {

    static public function primary_input_hidden() {
        parent::generic_input_hidden("id", osc_item_id() ) ;
        return true;
    }

    static public function page_hidden() {
        parent::generic_input_hidden("page", 'item') ;
        return true;
    }

    static public function action_hidden() {
        parent::generic_input_hidden("action", 'contact_post') ;
        return true;
    }
    
    static public function your_name() {
        parent::generic_input_text("yourName", "", null, false);
        return true ;
    }
    
    static public function your_email() {
        parent::generic_input_text("yourEmail", "", null, false);
        return true ;
    }
    
    static public function your_phone_number() {
        parent::generic_input_text("phoneNumber", "", null, false);
        return true ;
    }
    
    static public function the_subject() {
        parent::generic_input_text("subject", "", null, false);
        return true ;
    }
    
    static public function your_message() {
        parent::generic_textarea("message", "");
        return true ;
    }

    static public function your_attachment() {
        echo '<input type="file" name="attachment" />';
    }

    static public function js_validation() { ?>
<script type="text/javascript">
    function validate_contact() {
        email = $("#yourEmail");
        message = $("#message");

        var pattern=/^([a-zA-Z0-9_\.-])+@([a-zA-Z0-9_\.-])+\.([a-zA-Z])+([a-zA-Z])+/;
        var num_error = 0;
        
        if(!pattern.test(email.val())){
            email.css('border', '1px solid red');
            num_error = num_error + 1;
        }

        if(message.val().length < 1) {
            message.css('border', '1px solid red');
            num_error = num_error + 1;
        }

        if(num_error > 0) {
            return false;
        }

        return true;
    }

    $(document).ready(function(){
        $("#yourEmail").focus(function(){
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
