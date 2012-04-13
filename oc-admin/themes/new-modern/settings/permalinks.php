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
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
    </head>
    <body>
        <script type="text/javascript">
            $(document).ready(function(){
                // Code for form validation
                $("form[name=permalinks_form]").validate({
                    rules: {
                        rewrite_item_url: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_page_url: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_cat_url: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_url: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_country: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_region: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_city: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_city_area: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_category: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_user: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_search_pattern: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_contact: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_feed: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_language: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_mark: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_send_friend: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_contact: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_activate: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_edit: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_delete: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_item_resource_delete: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_login: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_dashboard: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_logout: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_register: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_activate: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_activate_alert: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_profile: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_items: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_alerts: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_recover: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_forgot: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_change_password: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_change_email: {
                            required: true,
                            minlength: 1
                        },
                        rewrite_user_change_email_confirm: {
                            required: true,
                            minlength: 1
                        }
                    },
                    messages: {
                        rewrite_item_url: {
                            required: "<?php _e("Item url: this field is required"); ?>.",
                            minlength: "<?php _e("Item url: this field is required"); ?>."
                        },
                        rewrite_page_url: {
                            required: "<?php _e("Page url: this field is required"); ?>.",
                            minlength: "<?php _e("Page url: this field is required"); ?>."
                        },
                        rewrite_cat_url: {
                            required: "<?php _e("Categories url: this field is required"); ?>.",
                            minlength: "<?php _e("Categories url: this field is required"); ?>."
                        },
                        rewrite_search_url: {
                            required: "<?php _e("Search url: this field is required"); ?>.",
                            minlength: "<?php _e("Search url: this field is required"); ?>."
                        },
                        rewrite_search_country: {
                            required: "<?php _e("Search country: this field is required"); ?>.",
                            minlength: "<?php _e("Search country: this field is required"); ?>."
                        },
                        rewrite_search_region: {
                            required: "<?php _e("Search region: this field is required"); ?>.",
                            minlength: "<?php _e("Search region: this field is required"); ?>."
                        },
                        rewrite_search_city: {
                            required: "<?php _e("Search city: this field is required"); ?>.",
                            minlength: "<?php _e("Search city: this field is required"); ?>."
                        },
                        rewrite_search_city_area: {
                            required: "<?php _e("Search city area: this field is required"); ?>.",
                            minlength: "<?php _e("Search city area: this field is required"); ?>."
                        },
                        rewrite_search_category: {
                            required: "<?php _e("Search category: this field is required"); ?>.",
                            minlength: "<?php _e("Search category: this field is required"); ?>."
                        },
                        rewrite_search_user: {
                            required: "<?php _e("Search user: this field is required"); ?>.",
                            minlength: "<?php _e("Search user: this field is required"); ?>."
                        },
                        rewrite_search_pattern: {
                            required: "<?php _e("Search pattern: this field is required"); ?>.",
                            minlength: "<?php _e("Search pattern: this field is required"); ?>."
                        },
                        rewrite_contact: {
                            required: "<?php _e("Contact url: this field is required"); ?>.",
                            minlength: "<?php _e("Contact url: this field is required"); ?>."
                        },
                        rewrite_feed: {
                            required: "<?php _e("Feed url: this field is required"); ?>.",
                            minlength: "<?php _e("Feed url: this field is required"); ?>."
                        },
                        rewrite_language: {
                            required: "<?php _e("Language url: this field is required"); ?>.",
                            minlength: "<?php _e("Language url: this field is required"); ?>."
                        },
                        rewrite_item_mark: {
                            required: "<?php _e("Item mark url: this field is required"); ?>.",
                            minlength: "<?php _e("Item mark url: this field is required"); ?>."
                        },
                        rewrite_item_send_friend: {
                            required: "<?php _e("Item send friend url: this field is required"); ?>.",
                            minlength: "<?php _e("Item send friend url: this field is required"); ?>."
                        },
                        rewrite_item_contact: {
                            required: "<?php _e("Item contact url: this field is required"); ?>.",
                            minlength: "<?php _e("Item contact url: this field is required"); ?>."
                        },
                        rewrite_item_new: {
                            required: "<?php _e("New item url: this field is required"); ?>.",
                            minlength: "<?php _e("New item url: this field is required"); ?>."
                        },
                        rewrite_item_activate: {
                            required: "<?php _e("Activate item url: this field is required"); ?>.",
                            minlength: "<?php _e("Activate item url: this field is required"); ?>."
                        },
                        rewrite_item_edit: {
                            required: "<?php _e("Edit item url: this field is required"); ?>.",
                            minlength: "<?php _e("Edit item url: this field is required"); ?>."
                        },
                        rewrite_item_delete: {
                            required: "<?php _e("Delete item url: this field is required"); ?>.",
                            minlength: "<?php _e("Delete item url: this field is required"); ?>."
                        },
                        rewrite_item_resource_delete: {
                            required: "<?php _e("Delete item resource url: this field is required"); ?>.",
                            minlength: "<?php _e("Delete item resource url: this field is required"); ?>."
                        },
                        rewrite_user_login: {
                            required: "<?php _e("Login url: this field is required"); ?>.",
                            minlength: "<?php _e("Login url: this field is required"); ?>."
                        },
                        rewrite_user_dashboard: {
                            required: "<?php _e("User dashboard url: this field is required"); ?>.",
                            minlength: "<?php _e("User dashboard url: this field is required"); ?>."
                        },
                        rewrite_user_logout: {
                            required: "<?php _e("Logout url: this field is required"); ?>.",
                            minlength: "<?php _e("Logout url: this field is required"); ?>."
                        },
                        rewrite_user_register: {
                            required: "<?php _e("User register url: this field is required"); ?>.",
                            minlength: "<?php _e("User register url: this field is required"); ?>."
                        },
                        rewrite_user_activate: {
                            required: "<?php _e("Activate user url: this field is required"); ?>.",
                            minlength: "<?php _e("Activate user url: this field is required"); ?>."
                        },
                        rewrite_user_activate_alert: {
                            required: "<?php _e("Activate alert url: this field is required"); ?>.",
                            minlength: "<?php _e("Activate aler url: this field is required"); ?>."
                        },
                        rewrite_user_profile: {
                            required: "<?php _e("User profile url: this field is required"); ?>.",
                            minlength: "<?php _e("User profile url: this field is required"); ?>."
                        },
                        rewrite_user_items: {
                            required: "<?php _e("User items url: this field is required"); ?>.",
                            minlength: "<?php _e("User items url: this field is required"); ?>."
                        },
                        rewrite_user_alerts: {
                            required: "<?php _e("User alerts url: this field is required"); ?>.",
                            minlength: "<?php _e("User alerts url: this field is required"); ?>."
                        },
                        rewrite_user_recover: {
                            required: "<?php _e("Recover user url: this field is required"); ?>.",
                            minlength: "<?php _e("Recover user url: this field is required"); ?>."
                        },
                        rewrite_user_forgot: {
                            required: "<?php _e("User forgot url: this field is required"); ?>.",
                            minlength: "<?php _e("User forgot url: this field is required"); ?>."
                        },
                        rewrite_user_change_password: {
                            required: "<?php _e("Change password url: this field is required"); ?>.",
                            minlength: "<?php _e("Change password url: this field is required"); ?>."
                        },
                        rewrite_user_change_email: {
                            required: "<?php _e("Change email url: this field is required"); ?>.",
                            minlength: "<?php _e("Change email url: this field is required"); ?>."
                        },
                        rewrite_user_change_email_confirm: {
                            required: "<?php _e("Change email confirm url: this field is required"); ?>.",
                            minlength: "<?php _e("Change email confirm url: this field is required"); ?>."
                        }
                    },
                    wrapper: "li",
                        errorLabelContainer: "#error_list",
                        invalidHandler: function(form, validator) {
                            $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
                        }
                });
            });


            $(function() {
                $("#rewrite_enabled").click(function(){
                    $("#custom_rules").toggle();
                });
            });
        </script>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="settings"><?php _e('Permalinks Settings') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
                <!-- settings form -->
                <div class="settings permalinks">
                    <ul id="error_list"></ul>
                    <form name="permalinks_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="settings" />
                        <input type="hidden" name="action" value="permalinks_post" />
                        <fieldset>
                            <div class="input-line">
                            <p>
                                <?php _e('By default OSClass uses web URLs which have question marks and lots of numbers in them. However, OSClass offers you friendly urls. This can improve the aesthetics, usability, and forward-compatibility of your links'); ?>
                            </p>
                            </div>
                            <div class="input-line">
                                <label class="checkbox">
                                    <input type="checkbox" <?php echo ( osc_rewrite_enabled() ? 'checked="true"' : '' ) ; ?> name="rewrite_enabled" id="rewrite_enabled" value="1" />
                                    <?php _e('Enable friendly urls') ; ?>
                                </label>
                            </div>

                            <div id="custom_rules" <?php if( !osc_rewrite_enabled() ) { echo 'style="display:none;"'; } ?>>
                                <h2><?php _e('Rewrite rules') ; ?></h2>
                                <div class="input-line">
                                    <label for="rewrite_item_url"><?php echo sprintf(__('Item URL. Accepted keywords: %s'), '{ITEM_ID},{ITEM_TITLE},{CATEGORIES}') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_item_url" id="rewrite_item_url" value="<?php echo osc_get_preference('rewrite_item_url'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_page_url"><?php echo sprintf(__('Page URL. Accepted keywords: %s'), '{PAGE_ID}, {PAGE_SLUG}') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_page_url" id="rewrite_page_url" value="<?php echo osc_get_preference('rewrite_page_url'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_cat_url"><?php echo sprintf(__('Category URL. Accepted keywords: %s'), '{CATEGORY_ID},{CATEGORY_NAME},{CATEGORIES}') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_cat_url" id="rewrite_cat_url" value="<?php echo osc_get_preference('rewrite_cat_url'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_search_url"><?php _e('Search URL:'); ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_search_url" id="rewrite_search_url" value="<?php echo osc_get_preference('rewrite_search_url'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_search_country"><?php _e('Search keyword country') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_search_country" id="rewrite_search_country" value="<?php echo osc_get_preference('rewrite_search_country'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_search_region"><?php _e('Search keyword region') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_search_region" id="rewrite_search_region" value="<?php echo osc_get_preference('rewrite_search_region'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_search_city"><?php _e('Search keyword city') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_search_city" id="rewrite_search_city" value="<?php echo osc_get_preference('rewrite_search_city'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_search_city_area"><?php _e('Search keyword city area') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_search_city_area" id="rewrite_search_city_area" value="<?php echo osc_get_preference('rewrite_search_city_area'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_search_category"><?php _e('Search keyword category') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_search_category" id="rewrite_search_category" value="<?php echo osc_get_preference('rewrite_search_category'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_search_user"><?php _e('Search keyword user') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_search_user" id="rewrite_search_user" value="<?php echo osc_get_preference('rewrite_search_user'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_search_pattern"><?php _e('Search keyword pattern') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_search_pattern" id="rewrite_search_pattern" value="<?php echo osc_get_preference('rewrite_search_pattern'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_contact"><?php _e('Contact') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_contact" id="rewrite_contact" value="<?php echo osc_get_preference('rewrite_contact'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_feed"><?php _e('Feed') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_feed" id="rewrite_feed" value="<?php echo osc_get_preference('rewrite_feed'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_language"><?php _e('Language') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_language" id="rewrite_language" value="<?php echo osc_get_preference('rewrite_language'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_item_mark"><?php _e('Item mark') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_item_mark" id="rewrite_item_mark" value="<?php echo osc_get_preference('rewrite_item_mark'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_item_send_friend"><?php _e('Item send friend') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_item_send_friend" id="rewrite_item_send_friend" value="<?php echo osc_get_preference('rewrite_item_send_friend'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_item_contact"><?php _e('Item contact') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_item_contact" id="rewrite_item_contact" value="<?php echo osc_get_preference('rewrite_item_contact'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_item_new"><?php _e('Item new') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_item_new" id="rewrite_item_new" value="<?php echo osc_get_preference('rewrite_item_new'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_item_activate"><?php _e('Item activate') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_item_activate" id="rewrite_item_activate" value="<?php echo osc_get_preference('rewrite_item_activate'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_item_edit"><?php _e('Item edit') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_item_edit" id="rewrite_item_edit" value="<?php echo osc_get_preference('rewrite_item_edit'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_item_delete"><?php _e('Item delete') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_item_delete" id="rewrite_item_delete" value="<?php echo osc_get_preference('rewrite_item_delete'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_item_resource_delete"><?php _e('Item resource delete') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_item_resource_delete" id="rewrite_item_resource_delete" value="<?php echo osc_get_preference('rewrite_item_resource_delete'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_login"><?php _e('User login') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_login" id="rewrite_user_login" value="<?php echo osc_get_preference('rewrite_user_login'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_dashboard"><?php _e('User dashboard') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_dashboard" id="rewrite_user_dashboard" value="<?php echo osc_get_preference('rewrite_user_dashboard'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_logout"><?php _e('User logout') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_logout" id="rewrite_user_logout" value="<?php echo osc_get_preference('rewrite_user_logout'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_register"><?php _e('User register') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_register" id="rewrite_user_register" value="<?php echo osc_get_preference('rewrite_user_register'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_activate"><?php _e('User activate') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_activate" id="rewrite_user_activate" value="<?php echo osc_get_preference('rewrite_user_activate'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_activate_alert"><?php _e('User activate alert') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_activate_alert" id="rewrite_user_activate_alert" value="<?php echo osc_get_preference('rewrite_user_activate_alert'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_profile"><?php _e('User profile') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_profile" id="rewrite_user_profile" value="<?php echo osc_get_preference('rewrite_user_profile'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_items"><?php _e('User items') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_items" id="rewrite_user_items" value="<?php echo osc_get_preference('rewrite_user_items'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_alerts"><?php _e('User alerts') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_alerts" id="rewrite_user_alerts" value="<?php echo osc_get_preference('rewrite_user_alerts'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_recover"><?php _e('User recover') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_recover" id="rewrite_user_recover" value="<?php echo osc_get_preference('rewrite_user_recover'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_forgot"><?php _e('User forgot') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_forgot" id="rewrite_user_forgot" value="<?php echo osc_get_preference('rewrite_user_forgot'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_change_password"><?php _e('User change password') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_change_password" id="rewrite_user_change_password" value="<?php echo osc_get_preference('rewrite_user_change_password'); ?>" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_change_email"><?php _e('User change email') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_change_email" id="rewrite_user_change_email" value="<?php echo osc_get_preference('rewrite_user_change_email'); ?>" />
                                    </div>

                                </div>
                                <div class="input-line">
                                    <label for="rewrite_user_change_email_confirm"><?php _e('User change email confirm') ; ?></label>
                                    <div class="input">
                                        <input class="xlarge" type="text" name="rewrite_user_change_email_confirm" id="rewrite_user_change_email_confirm" value="<?php echo osc_get_preference('rewrite_user_change_email_confirm'); ?>" />
                                    </div>
                                </div>
                            </div>

                            <div class="actions">
                                <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                    
                    <h2><?php _e('Useful information') ; ?></h2>
                    <?php
                        $mod_rewrite = '';
                        if( apache_mod_loaded('mod_rewrite') ) {
                            $mod_rewrite = sprintf( __('Apache Module <a href="%s">mod_rewrite</a> is loaded'), 'http://httpd.apache.org/docs/2.0/mod/mod_rewrite.html' ) ;
                        } else {
                            $mod_rewrite = sprintf( __('Apache Module <a href="%s">mod_rewrite</a> is <b>not</b> loaded'), 'http://httpd.apache.org/docs/2.0/mod/mod_rewrite.html' ) ;
                        }
                        $htaccess_exist = false ;
                        $htaccess_text  = __("It doesn't exist <em>.htaccess</em> file") ;
                        if( file_exists( osc_base_path() . '.htaccess' ) ) {
                            $htaccess_exist = true ;
                            $htaccess_text  = __("It exists <em>.htaccess</em> file. Below you can see the content of the file:") ;
                        }
                    ?>
                    <ul>
                        <li>
                            <?php echo $mod_rewrite ; ?>
                        </li>
                        <li>
                            <?php
                                echo $htaccess_text ;
                                if( $htaccess_exist && is_readable( osc_base_path() . '.htaccess' ) ) {
                                    echo '<pre>' ;
                                    echo osc_esc_html( file_get_contents(osc_base_path() . '.htaccess') ) ;
                                    echo '</pre>' ;
                                }
                            ?>
                        </li>
                    </ul>
                    
                </div>
                <!-- /settings form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>
