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

    <?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>

    <div id="right_column">
        <div id="content_header" class="content_header">
            <div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/settings-icon.png" /></div>
            <div id="content_header_arrow">&raquo; <?php _e('Comments'); ?></div>
            <div style="clear: both;"></div>
        </div>

        <div id="content_separator"></div>
        <?php osc_show_flash_messages() ; ?>
        <!-- settings form -->
        <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
            <div style="padding: 20px;">

                <form action="settings.php" method="post">
                    <input type="hidden" name="action" value="comments_post" />

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Settings'); ?></legend>
                            <?php if(osc_comments_enabled()) { ?>
                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" checked="true" name="enabled_comments" id="enabled_comments" />
                            <?php } else { ?>
                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="enabled_comments" id="enabled_comments" />
                            <?php } ?>
                            <label><?php _e('Comments enabled'); ?></label>

                            <br/>

                            <?php if(osc_moderate_comments()) { ?>
                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" checked="true" name="moderate_comments" id="moderate_comments" />
                            <?php } else { ?>
                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="moderate_comments" id="moderate_comments" />
                            <?php } ?>
                            <label><?php _e('Moderate comments') ; ?></label>
                        </fieldset>
                    </div>
                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Notifications') ; ?></legend>
                            <?php if(osc_notify_new_comment()) { ?>
                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" checked="true" name="notify_new_comment" id="notify_new_comment" />
                            <?php } else { ?>
                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="notify_new_comment" id="notify_new_comment" />
                            <?php } ?>
                            <label><?php _e('Notify new comment'); ?></label>
                        </fieldset>
                    </div>

                    <div style="clear: both;"></div>

                    <input id="button_save" type="submit" value="<?php _e('Update'); ?>" />

                </form>

            </div>
        </div>
    </div> <!-- end of right column -->
