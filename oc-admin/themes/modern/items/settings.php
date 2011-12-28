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
    <script type="text/javascript">
        function checkbox_change() {
            var on = $("#moderate_items").is(':checked');
            if(on==1) {
                $("#logged_user_item_validation").attr('disabled', false);
                $("#num_moderate_items_div").show();
                $("#num_moderate_items").val(0);
            } else {
                $("#logged_user_item_validation").attr('disabled', true);
                $("#num_moderate_items_div").hide();
            }
        };
    </script>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div id="content">
            <div id="separator"></div>
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/settings-icon.png') ; ?>" alt="" title="" />
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Items'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- settings form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="items" />
                            <input type="hidden" name="action" value="settings_post" />

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Settings'); ?></legend>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_recaptcha_items_enabled() ? 'checked="true"' : ''); ?> name="enabled_recaptcha_items" id="enabled_recaptcha_items" value="1" />
                                    <label for="enabled_recaptcha_items"><?php _e('Enable reCAPTCHA'); ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo ((osc_moderate_items()==-1) ? '' : 'checked="true"'); ?> name="moderate_items" onclick="checkbox_change();" id="moderate_items" value="1" />
                                    <label><?php _e('Enable item validation by users'); ?></label>
                                    <br />
                                    <div name="num_moderate_items_div" id="num_moderate_items_div" <?php echo ((osc_moderate_items()==-1) ? 'style="display:none"' : ''); ?>>
                                        &nbsp;<label><?php _e('Number of items from same author that should be validated before skipping validation (0 for always moderation)'); ?></label>
                                        <input type="text" name="num_moderate_items" id="num_moderate_items" value="<?php echo ((osc_moderate_items()==-1) ? '' : osc_moderate_items()); ?>" />
                                    </div>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_logged_user_item_validation() ? 'checked="true"' : ''); ?> name="logged_user_item_validation" id="logged_user_item_validation" value="1" <?php echo ((osc_moderate_items()!=-1 )? '' : 'disabled'); ?>/>
                                    <label for="logged_user_item_validation"><?php _e('Logged users don\'t need to validate items'); ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_reg_user_post() ? 'checked="true"' : ''); ?> name="reg_user_post" id="reg_user_post" value="1" />
                                    <label for="reg_user_post"><?php _e('Only allow registered users to post items'); ?></label>
                                    <br/>
                                    <label><?php _e('Number of seconds an user should wait before publishing another item (0 for no wait)'); ?></label>
                                    <input type="text" name="items_wait_time" id="items_wait_time" value="<?php echo osc_items_wait_time(); ?>" />
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_reg_user_can_contact() ? 'checked="true"' : ''); ?> name="reg_user_can_contact" onclick="checkbox_change();" id="reg_user_can_contact" value="1" />
                                    <label for="reg_user_can_contact"><?php _e('Only allow registered users to contact to seller'); ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_item_attachment() ? 'checked="true"' : ''); ?> name="item_attachment" onclick="checkbox_change();" id="item_attachment" value="1" />
                                    <label for="item_attachment"><?php _e('Allow attachment files in contact item seller'); ?></label>
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Notifications'); ?></legend>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_notify_new_item() ? 'checked="true"' : ''); ?> name="notify_new_item" id="notify_new_item" value="1" />
                                    <label for="notify_new_item"><?php _e('Notify admin of new items'); ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_notify_contact_item() ? 'checked="true"' : ''); ?> name="notify_contact_item" id="notify_contact_item" value="1" />
                                    <label for="notify_contact_item"><?php _e('Notify admin of contact items'); ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_notify_contact_friends() ? 'checked="true"' : ''); ?> name="notify_contact_friends" id="notify_contact_friends" value="1" />
                                    <label for="notify_contact_friends"><?php _e('Notify admin of share'); ?></label>
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Optional fields'); ?></legend>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_price_enabled_at_items() ? 'checked="true"' : ''); ?> name="enableField#f_price@items" id="enableField#f_price@items" value="1"  />
                                    <label for="enableField#f_price@items"><?php _e('Enable price'); ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_images_enabled_at_items() ? 'checked="true"' : ''); ?> name="enableField#images@items" id="enableField#images@items" value="1" />
                                    <label for="enableField#images@items"><?php _e('Enable images'); ?></label>
                                    <br/>
                                    <label for="numImages@items"><?php _e('Maximum number of images per item (0 for unlimited)'); ?></label>
                                    <input type="text" name="numImages@items" id="numImages@items" value="<?php echo osc_max_images_per_item(); ?>" />
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