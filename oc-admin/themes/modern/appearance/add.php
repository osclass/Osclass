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

<?php defined('ABS_PATH') or die( __('Invalid OSClass request.') ); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php $this->osc_print_head() ; ?>
    </head>
    <body>
        <?php $this->osc_print_header() ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header">Dashboard</div>

        <div id="content">
            <div id="separator"></div>

            <?php include_once osc_current_admin_theme_path() . 'include/backoffice_menu.php'; ?>

            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;"><img src="<?php echo  osc_current_admin_theme_url() ; ?>images/back_office/pages-icon.png" /></div>
                    <div id="content_header_arrow">&raquo; <?php _e('Add New Page') ; ?></div>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>

                <?php osc_show_flash_message() ; ?>

                <!-- add new plugin form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">

                        <?php if(is_writable(osc_themes_path())) { ?>

                            <form action="<?php echo osc_admin_base_url(true); ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="add_post" />
                                <input type="hidden" name="page" value="appearance"/>
                                <p>
                                    <label for="package"><?php _e('Theme package') ; ?> (.zip)</label>
                                    <input type="file" name="package" id="package" />
                                </p>
                                <input id="button_save" type="submit" value="<?php _e('Upload theme') ; ?>" />
                            </form>

                        <?php } else { ?>

                            <div id="flash_message">
                                <p>
                                    <?php _e('The themes folder ') ; ?> (<?php echo osc_themes_path() ; ?>) <?php _e(' is not writable on your server, '); ?> <span class="OSClass"><?php _e('OSClass'); ?></span> <?php _e(" can't upload themes from the administration panel. Please copy the theme package using another technique (FTP, SSH) or make the mentioned themes folder writable."); ?>
                                </p>
                                <p>
                                    <?php _e('To make a directory writable under UNIX execute this command from the shell:'); ?>
                                </p>
                                <p style="background-color: white; border: 1px solid black; padding: 8px;">
                                    chmod a+w <?php echo osc_themes_path(); ?>
                                </p>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    <?php $this->osc_print_footer() ; ?>

    </body>

</html>