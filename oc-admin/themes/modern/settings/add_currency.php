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
                        <img src="<?php echo osc_current_admin_theme_url('images/settings-icon.png') ; ?>" alt="" title=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Currencies'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="settings" />
                            <input type="hidden" name="action" value="currencies" />
                            <input type="hidden" name="type" value="add_post" />
                            <fieldset>
                                <legend><?php _e('Create currency'); ?></legend>
                                <p>
                                    <label for="code"><?php _e('Code'); ?></label><br />
                                    <input type="text" name="pk_c_code" id="code" />
                                    <span><?php _e('It should be a three-character code'); ?>.</span>
                                </p>

                                <p>
                                    <label for="name"><?php _e('Name'); ?></label><br />
                                    <input type="text" name="s_name" id="name" />
                                </p>

                                <p>
                                    <label for="description"><?php _e('Description'); ?></label><br />
                                    <input type="text" name="s_description" id="description" />
                                </p>
                            </fieldset>
                            <input id="button_save" onclick="javascript:history.back();" value="<?php osc_esc_html(_e('Cancel')); ?>" />
                            <input id="button_save" type="submit" value="<?php osc_esc_html(_e('Create')); ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>