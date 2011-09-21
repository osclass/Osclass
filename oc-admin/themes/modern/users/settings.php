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
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div id="content">
            <div id="separator"></div>
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url( 'images/settings-icon.png' ) ; ?>" alt="" title="" />
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Users settings') ; ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- settings form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="users" />
                            <input type="hidden" name="action" value="settings_post" />

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Settings'); ?></legend>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="enabled_users" id="enabled_users" <?php echo (osc_users_enabled() ? 'checked="checked"' : ''); ?> value="1" />
                                    <label for="enabled_users"><?php _e('Users enabled') ; ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="enabled_user_validation" id="enabled_user_validation" <?php echo (osc_user_validation_enabled() ? 'checked="checked"' : ''); ?> value="1" />
                                    <label for="enabled_user_validation"><?php _e('User validation enabled') ; ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="enabled_user_registration" id="enabled_user_registration" <?php echo (osc_user_registration_enabled() ? 'checked="checked"' : ''); ?> value="1" />
                                    <label for="enabled_user_registration"><?php _e('User registration enabled') ; ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="notify_new_user" id="notify_new_user" <?php echo (osc_notify_new_user() ? 'checked="checked"' : ''); ?> value="1" />
                                    <label for="notify_new_user"><?php _e('Notify admin when a new user registers') ; ?></label>
                                </fieldset>
                            </div>
                            <div style="clear: both;"></div>
                            <input id="button_save" type="submit" value="<?php _e('Update') ; ?>" />
                        </form>
                    </div>
                </div>
            </div> <!-- end of right column -->
        </div><!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>