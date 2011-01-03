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
<script type="text/javascript">
function validateLoginForm() {
	var validator = new FormValidator();
	try {
		validator.addValidation('userName', FormValidator.TYPE_REGEX, '[a-zA-Z0-9_]{5}');
		validator.addValidation('password', FormValidator.TYPE_COMPLETED);
		return validator.run();
	} catch(e) {
		alert(e);
		return false;
	}
}
</script>

<h2><?php _e('Access to your account'); ?></h2>

<form action="<?php echo osc_createURL('user');?>" method="post" onsubmit="return validateLoginForm();">
<input type="hidden" name="action" value="login_post" />

<p>
<label for="userName"><?php _e('User name'); ?></label><br />
<?php UserForm::username_login_text();?>
</p>

<p>
<label for="password"><?php _e('Password'); ?></label><br />
<?php UserForm::password_login_text();?>
</p>

<p>
<?php UserForm::rememberme_login_checkbox();?> <label for="rememberMe"><?php _e('Remember me'); ?></label>
</p>

<p>
<input type="submit" value="Login" />
</p>

</form>
