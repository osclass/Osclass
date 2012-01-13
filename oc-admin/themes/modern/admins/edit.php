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
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header"><?php _e('Admins'); ?></div>
        <div id="content">
            <div id="separator"></div>
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>

            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo  osc_current_admin_theme_url('images/admin-icon.png') ; ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo;<?php _e('Edit admin'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin'); ?>
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">				
                        <form action="<?php echo osc_admin_base_url(true);?>" method="post" onSubmit="return checkForm()">
                            <input type="hidden" name="action" value="edit_post" />
                            <input type="hidden" name="page" value="admins" />
                            <input type="hidden" name="id" value="<?php echo $adminEdit['pk_i_id']; ?>" />

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Real name'); ?> (<?php _e('required'); ?>)</legend>
                                    <input type="text" name="s_name" id="s_name" value="<?php echo osc_esc_html($adminEdit['s_name']); ?>" />
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('E-mail'); ?></legend>
                                    <input type="text" name="s_email" id="s_email" value="<?php echo osc_esc_html($adminEdit['s_email']); ?>" />
                                </fieldset>
                            </div>
                            <div style="clear: both;"></div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('User name'); ?> (<?php _e('required'); ?>)</legend>
                                    <input type="text" name="s_username" id="s_username" value="<?php echo osc_esc_html($adminEdit['s_username']); ?>" />
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Current password'); ?></legend>
                                    <input type="password" name="old_password" id="old_password" value="" />
                                    <legend><?php _e('New password'); ?></legend>
                                    <input type="password" name="s_password" id="s_password" value="" />
                                    <legend><?php _e('Re-type new password'); ?></legend>
                                    <input type="password" name="s_password2" id="password2" value="" />
                                </fieldset>
                                <div style="margin-left: 10px; width: 95%; padding: 4px; background: #FFD2CF;"><?php _e('Leave it blank if you don\'t want to change your password'); ?>.</div>
                            </div>
                            <div style="clear: both;"></div>
                            <input id="button_save" type="submit" value="<?php osc_esc_html(_e('Update')); ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>