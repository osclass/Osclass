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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_web_theme_path('head.php') ; ?>
    </head>
    <body>
        <div class="container">
            <?php osc_current_web_theme_path('header.php') ; ?>
            <div class="content user_forms">
                <div class="inner">
                    <h1><?php _e('Register an account for free', 'gui') ; ?></h1>
                    <form action="<?php echo osc_base_url(true) ; ?>" method="post" onSubmit="javascript:return checkForm();">
                        <input type="hidden" name="page" value="register" />
                        <input type="hidden" name="action" value="register_post" />
                        
                        <fieldset>
                            <label for="name"><?php _e('Name', 'gui') ; ?></label> <?php UserForm::name_text(); ?><br />
                            <label for="password"><?php _e('Password', 'gui') ; ?></label> <?php UserForm::password_register_text(); ?><br />
                            <label for="password"><?php _e('Re-type password', 'gui') ; ?></label> <?php UserForm::check_password_register_text(); ?><br />
                            <p id="password-error" style="display:none;">
                                <?php _e('Passwords don\'t match', 'gui') ; ?>.
                            </p>
                            <label for="email"><?php _e('E-mail', 'gui') ; ?></label> <?php UserForm::email_text() ; ?><br />
                            <?php
                            if( osc_recaptcha_public_key() ) {
                                require_once 'recaptchalib.php' ;
                                echo recaptcha_get_html( osc_recaptcha_public_key() ) ;
                            }
                            ?>
                            <button type="submit"><?php _e('Create', 'gui') ; ?></button>
                            <?php osc_run_hook('user_register_form') ; ?>
                        </fieldset>
                    </form>
                </div>
            </div>
            <?php UserForm::js_validation() ; ?>
            <?php osc_current_web_theme_path('footer.php') ; ?>
        </div>
        <?php osc_show_flash_message() ; ?>
    </body>
</html>