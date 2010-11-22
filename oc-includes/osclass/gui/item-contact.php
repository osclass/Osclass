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
<div id="home_header"><div><?php _e('Contact seller'); ?></div></div>
<?php ContactForm::js_validation(); ?>
<form action="item.php" method="post" onsubmit="return validate_contact();">
<input type="hidden" name="action" value="contact_post" />
<input type="hidden" name="id" value="<?php echo $item['pk_i_id']; ?>" />

		<table>
		<tr>
			<td><?php _e('To (seller)'); ?></td>
			<td><?php echo $item['s_contact_name']; ?></td>
		</tr>
		<tr>
			<td><?php _e('Item'); ?></td>
			<td><a href="<?php echo osc_createItemURL($item); ?>"><?php echo $item['s_title']; ?></a></td>
		</tr>
		<tr>
			<td><label for="yourName"><?php _e('Your name'); ?></label> <?php _e('(optional)'); ?></td>
			<td><?php ContactForm::your_name(); ?></td>
		</tr>
		<tr>
			<td><label for="yourEmail"><?php _e('Your email address'); ?></label></td>
			<td><?php ContactForm::your_email(); ?></td>
		</tr>
		<tr>
			<td><label for="phoneNumber"><?php _e('Phone number'); ?></label> <?php _e('(optional)'); ?></td>
			<td><?php ContactForm::your_phone_number(); ?></td>
		</tr>
		<tr>
			<td><label for="message"><?php _e('Message'); ?></label></td>
			<td><?php ContactForm::your_message(); ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><button type="submit"><?php _e('Send message'); ?></button></td>
		</tr>
		</table>
</form>
