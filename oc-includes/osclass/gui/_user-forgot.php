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
<h2><?php _e('Retrieve your password') ; ?></h2>

<form action="<?php echo osc_create_url('user') ; ?>" method="post">
    <input type="hidden" name="action" value="forgot_post" />

    <p>
        <label for="email"><?php _e('E-mail') ; ?></label><br />
        <?php UserForm::email_text(); ?>
    </p>

    <p>
        <?php _e('If you forgot your password, enter your e-mail and we\'ll send you an e-mail to recover it.') ; ?>
    </p>

    <p>
        <input type="submit" value="<?php _e('I forgot my password') ; ?>" />
    </p>
</form>
