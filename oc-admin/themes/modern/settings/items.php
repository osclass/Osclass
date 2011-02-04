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

    defined('ABS_PATH') or die(__('Invalid OSClass request.'));

    $dateFormats = array('F j, Y', 'Y/m/d', 'm/d/Y', 'd/m/Y');
    $timeFormats = array('g:i a', 'g:i A', 'H:i');
?>
<div id="content">
    <div id="separator"></div>

    <?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>

    <div id="right_column">
        <div id="content_header" class="content_header">
            <div style="float: left;">
                <img src="<?php echo $current_theme; ?>/images/back_office/settings-icon.png" title="" alt="" />
            </div>
            <div id="content_header_arrow">&raquo; <?php _e('Items'); ?></div>
            <div style="clear: both;"></div>
        </div>
				
        <div id="content_separator"></div>
        <?php osc_showFlashMessages(); ?>
        <!-- settings form -->
        <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
            <div style="padding: 20px;">

                <form action="settings.php" method="post">
                    <input type="hidden" name="action" value="items_post" />

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Settings') ; ?></legend>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_enabled_recaptcha_items() ? 'checked="true"' : ''); ?> name="enabled_recaptcha_items" id="enabled_recaptcha_items" />
                            <label><?php _e('Enabled recaptcha') ; ?></label>
                            <br/>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_enabled_item_validation() ? 'checked="true"' : ''); ?> name="enabled_item_validation" id="enabled_item_validation" />
                            <label><?php _e('Enabled item validation') ; ?></label>
                            <br/>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_reg_user_post() ? 'checked="true"' : ''); ?> name="reg_user_post" id="reg_user_post" />
                            <label><?php _e('Only allow registered users post items') ; ?></label>
                        </fieldset>
                    </div>

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Notifications'); ?></legend>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_notify_new_item() ? 'checked="true"' : ''); ?> name="notify_new_item" id="notify_new_item" />
                            <label><?php _e('Notify new item to admin') ; ?></label>
                            <br/>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_notify_contact_item() ? 'checked="true"' : ''); ?> name="notify_contact_item" id="notify_contact_item" />
                            <label><?php _e('Notify contact item to admin') ; ?></label>
                            <br/>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_notify_contact_friends() ? 'checked="true"' : ''); ?> name="notify_contact_friends" id="notify_contact_friends" />
                            <label><?php _e('Notify contact friends to admin') ; ?></label>
                        </fieldset>
                    </div>
                    
                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Optional fields'); ?></legend>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_price_enabled_at_items() ? 'checked="true"' : ''); ?> name="enableField#f_price@items" />
                            <label><?php _e('Enable price') ; ?></label>
                            <br/>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_images_enabled_at_items() ?  'checked="true"' : ''); ?> name="enableField#images@items" />
                            <label><?php _e('Enable images') ; ?></label>
                        </fieldset>
                    </div>

                    <div style="clear: both;"></div>
												
                    <input id="button_save" type="submit" value="<?php _e('Update') ; ?>" />
						
                </form>
            </div>
        </div>
    </div> <!-- end of right column -->