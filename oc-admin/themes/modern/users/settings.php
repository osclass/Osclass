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
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="settings"><?php _e('Users Settings') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- users settings form -->
                <div class="settings users">
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="users" />
                        <input type="hidden" name="action" value="settings_post" />
                        <fieldset>
                            <table class="table-backoffice-form">
                                <tr>
                                    <td class="labeled"><?php _e('Settings') ; ?></td>
                                    <td><input type="checkbox" name="enabled_users" <?php echo ( osc_users_enabled() ? 'checked="checked"' : '' ) ; ?> value="1" />
                                        <?php _e('Users enabled') ; ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="checkbox" name="enabled_user_registration" <?php echo ( osc_user_registration_enabled() ? 'checked="checked"' : '' ) ; ?> value="1" />
                                        <?php _e('Anyone can register') ; ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="checkbox" name="enabled_user_validation" <?php echo ( osc_user_validation_enabled() ? 'checked="checked"' : '' ) ; ?> value="1" />
                                        <?php _e('Users need to validate their account') ; ?></td>
                                </tr>
                                <tr>
                                    <td class="labeled"><?php _e('Admin notifications') ; ?></td>
                                    <td><input type="checkbox" name="notify_new_user" <?php echo ( osc_notify_new_user() ? 'checked="checked"' : '' ) ; ?> value="1" />
                                        <?php _e('When a new user is registered') ; ?></td>
                                </tr>
                                <tr class="separate">
                                    <td></td>
                                    <td>
                                        <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" />
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </form>
                </div>
                <!-- /users settings form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>