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

<?php defined('ABS_PATH') or die(__('Invalid OSClass request.')); ?>

<?php
    $dateFormats = array('F j, Y', 'Y/m/d', 'm/d/Y', 'd/m/Y');
    $timeFormats = array('g:i a', 'g:i A', 'H:i');
?>

<div id="content">
    <div id="separator"></div>

    <?php include_once osc_current_admin_theme_path() . 'include/backoffice_menu.php'; ?>

    <div id="right_column">
        <div id="content_header" class="content_header">
            <div style="float: left;"><img src="<?php echo osc_current_admin_theme_url() ; ?>images/back_office/settings-icon.png" /></div>
            <div id="content_header_arrow">&raquo; <?php _e('Functionalities'); ?></div>
            <div style="clear: both;"></div>
        </div>

        <div id="content_separator"></div>

        <?php osc_show_flash_message() ; ?>

        <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
            <div style="padding: 20px;">

                <form action="settings.php" method="post">
                    <input type="hidden" name="action" value="cron_post" />

                    <div style="float: left; width: 100%;">

                        <fieldset>
                            <legend><?php _e('Cron System'); ?></legend>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php ( osc_auto_cron() ) ? 'checked="true"' : '' ; ?> name="auto_cron" id="auto_cron" />
                            <label><?php _e('Auto-cron'); ?></label>
                            <br/>
                            <label><?php _e('Some functionalities os OSClass requires a cron system to work. Check this if you don\'t know what a cron-job is or your host is not able to do them. Uncheck if you want to do your cron manually. Refer to the manual to know more about the cron system in OSClass.'); ?></label>
                        </fieldset>

                    </div>

                    <div style="clear: both;"></div>

                    <input id="button_save" type="submit" value="<?php _e('Update') ; ?>" />

                </form>

            </div>
        </div>
    </div>