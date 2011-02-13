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

<?php $locales = Locale::newInstance()->listAllEnabled(); ?>

<div id="header">
    <a id="logo" href="<?php echo osc_base_url() ; ?>"><strong><?php echo osc_page_title() ; ?></strong></a>
    <div id="user_menu">
        <ul>
            <?php if( osc_is_web_user_logged_in() ) { ?>
                <li class="first logged">
                    <?php _e('Hello ' . osc_logged_user_name() . '!') ; ?>  &middot;
                    <strong><a href="<?php echo osc_user_dashboard_url() ; ?>"><?php _e('My account') ; ?></a></strong> &middot;
                    <a href="<?php echo osc_user_logout_url() ; ?>"><?php _e('Logout') ; ?></a>
                </li>
            <?php } else { ?>
                <li class="first">
                    <a id="login_open" href="#"><?php _e('Login') ; ?></a>  &middot;
                    <a href="<?php echo osc_register_account_url() ; ?>"><?php _e('Register a free account') ; ?></a>
                    <form id="login" action="<?php osc_base_url(true) ; ?>" method="post">
                        <fieldset>
                            <input type="hidden" name="page" value="login" />
                            <input type="hidden" name="action" value="login_post" />
                            <label for="email"><?php _e('E-mail') ; ?></label>
                            <?php UserForm::email_login_text() ; ?>
                            <label for="password"><?php _e('Password') ; ?></label>
                            <?php UserForm::password_login_text() ; ?>
                            <button type="submit"><?php _e('Login') ; ?></button>
                        </fieldset>
                    </form>
                </li>
            <?php } ?>
            <li class="last with_sub">
                <strong><?php _e("Language") ; ?></strong>
                <ul>
                    <?php $i = 0 ;  ?>
                    <?php foreach($locales as $locale) { ?>
                        <li <?php if( $i == 0 ) { echo "class='first'" ; } ?>><a id="<?php echo $locale['pk_c_code'] ; ?>" href="<?php echo osc_change_language_url ($locale['pk_c_code']) ; ?>"><?php echo $locale['s_name'] ; ?></a></li>
                        <?php $i++ ; ?>
                    <?php } ?>
                </ul>
            </li>
        </ul>
    </div>
</div>

<?php osc_show_widgets('header') ; ?>