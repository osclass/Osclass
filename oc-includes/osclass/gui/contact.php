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
<h1><?php _e('Contact form'); ?></h1>

<form action="index.php" method="post">
<input type="hidden" name="action" value="contact_post" />

<table>
<tr>
	<td><label for="subject"><?php _e('Subject'); ?></label></td>
	<td><?php ContactForm::the_subject(); ?></td>
</tr>
<tr>
    <td><label for="message"><?php _e('Message'); ?></label></td>
    <td><?php ContactForm::your_message(); ?></td>
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
	<td colspan="2" style="text-align: right;"><input type="submit" value="<?php _e('Send'); ?>" /></td>
</tr>
</table>

</form>
