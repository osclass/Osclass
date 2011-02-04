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
?>
<h2><?php _e('Send to a friend'); ?></h2>

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
<form id="send-friend" name = "send-friend" action="<?php echo osc_create_url('item');?>" method="post">
<input type="hidden" name="action" value="send_friend_post" />
<input type="hidden" name="id" value="<?php echo $item['pk_i_id']; ?>" />

<table>
<tr>
	<td><?php _e('Item'); ?></td>
	<td><a href="<?php osc_create_item_url($item, true); ?>"><?php echo $item['s_title']; ?></a></td>
</tr>
<tr>
	<td><label for="yourName"><?php _e('Your name'); ?></label></td>
	<td><input type="text" name="yourName" id="yourName" /></td>
</tr>
<tr>
	<td><label for="yourEmail"><?php _e('Your email address'); ?></label></td>
	<td><input type="text" name="yourEmail" id="yourEmail" /></td>
</tr>
<tr>
	<td><label for="friendName"><?php _e("Your friend's name"); ?></label></td>
	<td><input type="text" name="friendName" id="friendName" /></td>
</tr>
<tr>
	<td><label for="friendEmail"><?php _e("Your friend's email address"); ?></label></td>
	<td><input type="text" name="friendEmail" id="friendEmail" /></td>
</tr>
<tr>
	<td><label for="message"><?php _e('Message'); ?></label></td>
	<td><textarea cols="50" name="message" id="message"></textarea></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input onclick="validate_form()" type="button" value="<?php _e('Send message'); ?>" />
</tr>
</table>

</form>
