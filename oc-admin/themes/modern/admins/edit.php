<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    //getting variables for this view
    $adminEdit = __get("admin") ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="admins"><?php _e('Edit admin') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
                <!-- add admin form -->
                <div class="settings general">
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="action" value="edit_post" />
                        <input type="hidden" name="page" value="admins" />
                        <input type="hidden" name="id" value="<?php echo $adminEdit['pk_i_id'] ; ?>" />
                        <fieldset>
                            <div class="input-line">
                                <label><?php _e('Name <em>(required)</em>') ; ?></label>
                                <div class="input">
                                    <input type="text" class="large" name="s_name" value="<?php echo osc_esc_html($adminEdit['s_name']) ; ?>" />
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Username <em>(required)</em>') ; ?></label>
                                <div class="input">
                                    <input type="text" class="large" name="s_username" value="<?php echo osc_esc_html($adminEdit['s_username']) ; ?>" />
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('E-mail <em>(required)</em>') ; ?></label>
                                <div class="input">
                                    <input type="text" class="large" name="s_email" value="<?php echo osc_esc_html($adminEdit['s_email']) ; ?>" />
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Current password') ; ?></label>
                                <div class="input">
                                    <input type="password" class="large" name="old_password" value="" />
                                    <p class="help-inline"><em><?php _e('If you would like to change the password type a new one. Otherwise leave this blank') ; ?></em></p>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('New password') ; ?></label>
                                <div class="input">
                                    <input type="password" class="large" name="s_password" value="" />
                                </div>
                                <div class="input">
                                    <input type="password" class="large" name="s_password2" value="" />
                                    <p class="help-inline"><em><?php _e('Type your new password again') ; ?></em></p>
                                </div>
                            </div>
                            <div class="actions">
                                <input type="submit" value="<?php echo osc_esc_html( __('Update admin') ) ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /add admin form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>