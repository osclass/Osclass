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
        <script type="text/javascript">
            $(document).ready(function() {
                $('input[name="moderate_items"]').bind('change', function() {
                    if( $(this).is(':checked') ) {
                        $('input[name="logged_user_item_validation"]').attr('disabled', false) ;
                        $(".num-moderated-items").show() ;
                        $('input[name="num_moderate_items"]').val(0) ;
                    } else {
                        $('input[name="logged_user_item_validation"]').attr('disabled', true) ;
                        $('.num-moderated-items').hide();
                    }
                }) ;
            }) ;
        </script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="settings"><?php _e('Items Settings') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
                <!-- settings items -->
                <div class="settings items">
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="items" />
                        <input type="hidden" name="action" value="settings_post" />
                        <fieldset>
                            <!-- settings -->
                            <div class="input-line">
                                <label><?php _e('Settings') ; ?></label>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_reg_user_post() ? 'checked="true"' : '') ; ?> name="reg_user_post" value="1" />
                                        <p class="inline"><?php _e('Only logged in users can post items') ; ?></p>
                                    </label>
                                </div>
                                <div class="input nested">
                                    <?php printf( __('An user has to wait %s seconds between each item added'), '<input type="text" class="micro" name="items_wait_time" value="' . osc_items_wait_time() . '" />') ; ?>
                                    <p class="help-inline"><?php _e('If the value is zero, there is no waiting') ; ?></p>
                                </div>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( ( osc_moderate_items() == -1 ) ? '' : 'checked="true"' ) ; ?> name="moderate_items" value="1" />
                                        <p class="inline"><?php _e('Users have to validate their items') ; ?></p>
                                    </label>
                                </div>
                                <div class="input num-moderated-items nested">
                                    <?php printf( __("After %s validated items the user doesn't longer need to validate the items"), '<input type="text" class="micro" name="num_moderate_items" value="' . ( ( osc_moderate_items() == -1 ) ? '' : osc_moderate_items() ) . '" />') ; ?>
                                    <p class="help-inline"><?php _e('If the value is zero, it means that each item must be validated') ; ?></p>
                                </div>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_logged_user_item_validation() ? 'checked="true"' : '' ) ; ?> name="logged_user_item_validation" value="1" <?php echo ( ( osc_moderate_items() != -1 ) ? '' : 'disabled') ; ?> />
                                        <p class="inline"><?php _e("Logged in users don't need to validate their items") ; ?></p>
                                    </label>
                                </div>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( ( osc_recaptcha_items_enabled() == '0' ) ? '' : 'checked="true"' ) ; ?> name="enabled_recaptcha_items" value="1" />
                                        <p class="inline"><?php _e('Show reCAPTCHA in add/edit item form') ; ?></p>
                                        <p class="help-inline"><?php _e('<strong>Remember</strong> that you must configure reCAPTCHA first') ; ?></p>
                                    </label>
                                </div>
                            </div>
                            <!-- /settings -->
                            <!-- contact publisher -->
                            <div class="input-line">
                                <label><?php _e('Contact publisher') ; ?></label>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_reg_user_can_contact() ? 'checked="true"' : '' ) ; ?> name="reg_user_can_contact" value="1" />
                                        <p class="inline"><?php _e('Only allow registered users to contact publisher') ; ?></p>
                                    </label>
                                </div>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_item_attachment() ? 'checked="true"' : '' ) ; ?> name="item_attachment" value="1" />
                                        <p class="inline"><?php _e('Allow attach files in contact publisher form') ; ?></p>
                                    </label>
                                </div>
                            </div>
                            <!-- /contact publisher -->
                            <!-- notifications -->
                            <div class="input-line">
                                <label><?php _e('Notifications') ; ?></label>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_notify_new_item() ? 'checked="true"' : '') ; ?> name="notify_new_item" value="1" />
                                        <p class="inline"><?php _e('Notify admin when a new item is added') ; ?></p>
                                    </label>
                                </div>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_notify_contact_item() ? 'checked="true"' : '' ) ; ?> name="notify_contact_item" value="1" />
                                        <p class="inline"><?php _e('Send a copy to admin of the contact publisher e-mail') ; ?></p>
                                    </label>
                                </div>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_notify_contact_friends() ? 'checked="true"' : '' ) ; ?> name="notify_contact_friends" value="1" />
                                        <p class="inline"><?php _e('Send a copy to admin of the share item e-mail') ; ?></p>
                                    </label>
                                </div>
                            </div>
                            <!-- /notifications -->
                            <!-- optional fields -->
                            <div class="input-line">
                                <label><?php _e('Optional fields') ; ?></label>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_price_enabled_at_items() ? 'checked="true"' : '' ) ; ?> name="enableField#f_price@items" value="1"  />
                                        <p class="inline"><?php _e('Price') ; ?></p>
                                    </label>
                                </div>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_images_enabled_at_items() ? 'checked="true"' : '' ) ; ?> name="enableField#images@items" value="1" />
                                        <p class="inline"><?php _e('Attach images') ; ?></p>
                                    </label>
                                </div>
                                <div class="input nested">
                                    <?php printf( __('Attach %s images per item'), '<input type="text" class="micro" name="numImages@items" value="' . osc_max_images_per_item() . '" />' ) ; ?>
                                    <p class="help-inline"><?php _e('If the value is zero, it means unlimited number of images') ; ?></p>
                                </div>
                            </div>
                            <!-- /optional fields -->
                            <div class="actions">
                                <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /settings items -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>