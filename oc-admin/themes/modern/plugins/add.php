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

                <?php osc_current_admin_theme_path('include/backoffice_menu.php') ; ?>

                <div id="right_column">
                    <div id="content_header" class="content_header">
                        <div style="float: left;">
                            <img src="<?php echo osc_current_admin_theme_url('images/plugins-icon.png') ; ?>" title="" alt=""/>
                        </div>
                        <div id="content_header_arrow">&raquo; <?php _e('Add a new plugin'); ?></div>
                        <div style="clear: both;"></div>
                    </div>

                    <div id="content_separator"></div>
                    <?php osc_show_flash_message('admin') ; ?>

                    <!-- add new plugin form -->
                    <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                        <div style="padding: 20px;">

                            <?php if(is_writable(osc_plugins_path())) { ?>

                                <p style="border-bottom: 1px black solid;padding-bottom: 10px;">
                                    <img style="padding-right: 10px;"src="<?php echo osc_current_admin_theme_url('images/info-icon.png') ; ?>"/>
                                    Download more plugins at <a href="https://sourceforge.net/projects/osclass/files/Plugins/" target="_blank">Sourceforge</a>
                                </p>

                                <form action="<?php echo osc_admin_base_url(true);?>?page=plugins" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="add_post" />
                                    <p>
                                        <label for="package"><?php _e('Plugin package'); ?> (.zip)</label>
                                        <input type="file" name="package" id="package" />
                                    </p>
                                    <input id="button_save" type="submit" value="<?php _e('Upload'); ?>" />
                                </form>

                            <?php } else { ?>

                                <div id="flash_message">
                                    <p>
                                        <?php
                                            $msg  = sprintf(__('The plugin folder %s is not writable on your server'), osc_plugins_path());
                                            $msg .= __('OSClass can\'t upload plugins from the administration panel') . '.';
                                            $msg .= __('Please copy the plugin package using FTP or SSH, or make the mentioned plugins folder writable') . '.';
                                            echo $msg;
                                        ?>
                                    </p>
                                    <p>
                                        <?php _e('To make a directory writable under UNIX execute this command from the shell'); ?>:
                                    </p>
                                    <p style="background-color: white; border: 1px solid black; padding: 8px;">
                                        chmod a+w <?php echo osc_plugins_path() ; ?>
                                    </p>
                                </div>

                            <?php } ?>

                        </div>
                    </div>
                </div>
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>