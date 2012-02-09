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
        <script type="text/javascript">
            $(function() {
                $("#rewrite_enabled").click(function(){
                    $("#custom_rules").toggle();
                });
            });
        </script>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
		    <div class="right">
                <div class="header_title">
                    <h1 class="settings"><?php _e('Permalinks Settings') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
				<!-- settings form -->
				<div class="settings permalinks">
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="settings" />
                        <input type="hidden" name="action" value="permalinks_post" />
                        <fieldset>
                            <p>
                                <?php _e('By default OSClass uses web URLs which have question marks and lots of numbers in them. However, OSClass offers you friendly urls. This can improve the aesthetics, usability, and forward-compatibility of your links'); ?>
                            </p>
                            <div class="input-line">
                                <label class="checkbox">
                                    <input type="checkbox" <?php echo ( osc_rewrite_enabled() ? 'checked="true"' : '' ) ; ?> name="rewrite_enabled" value="1" />
                                    <?php _e('Enable friendly urls') ; ?>
                                </label>
                            </div>

                            <div id="custom_rules" style="float: left; width: 100%;<?php if(!osc_rewrite_enabled()) { echo "display:none;";}?>">
                                <fieldset>
                                    <legend><?php _e('Rewrite rules'); ?></legend>
                                    <label for="rewrite_item_url"><?php echo sprintf(__('Item URL. Accepted keywords: %s'), '{ITEM_ID},{ITEM_TITLE},{CATEGORIES}') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_item_url" id="rewrite_item_url" value="<?php echo osc_get_preference('rewrite_item_url'); ?>" />
                                    <br/>
                                    <label for="rewrite_page_url"><?php echo sprintf(__('Page URL. Accepted keywords: %s'), '{PAGE_ID},{PAGE_SLUG}, {PAGE_TITLE}') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_page_url" id="rewrite_page_url" value="<?php echo osc_get_preference('rewrite_page_url'); ?>" />
                                    <br/>
                                    <label for="rewrite_cat_url"><?php echo sprintf(__('Category URL. Accepted keywords: %s'), '{CATEGORY_ID},{CATEGORY_NAME},{CATEGORIES}') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_cat_url" id="rewrite_cat_url" value="<?php echo osc_get_preference('rewrite_cat_url'); ?>" />
                                    <br/>
                                    <label for="rewrite_search_url"><?php _e('Search URL:'); ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_search_url" id="rewrite_search_url" value="<?php echo osc_get_preference('rewrite_search_url'); ?>" />
                                    <br/>
                                    <label for="rewrite_search_country"><?php _e('Search keyword country') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_search_country" id="rewrite_search_country" value="<?php echo osc_get_preference('rewrite_search_country'); ?>" />
                                    <br/>
                                    <label for="rewrite_search_region"><?php _e('Search keyword region') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_search_region" id="rewrite_search_region" value="<?php echo osc_get_preference('rewrite_search_region'); ?>" />
                                    <br/>
                                    <label for="rewrite_search_city"><?php _e('Search keyword city') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_search_city" id="rewrite_search_city" value="<?php echo osc_get_preference('rewrite_search_city'); ?>" />
                                    <br/>
                                    <label for="rewrite_search_city_area"><?php _e('Search keyword city area') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_search_city_area" id="rewrite_search_city_area" value="<?php echo osc_get_preference('rewrite_search_city_area'); ?>" />
                                    <br/>
                                    <label for="rewrite_search_category"><?php _e('Search keyword category') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_search_category" id="rewrite_search_category" value="<?php echo osc_get_preference('rewrite_search_category'); ?>" />
                                    <br/>
                                    <label for="rewrite_search_user"><?php _e('Search keyword user') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_search_user" id="rewrite_search_user" value="<?php echo osc_get_preference('rewrite_search_user'); ?>" />
                                    <br/>
                                    <label for="rewrite_search_pattern"><?php _e('Search keyword pattern') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_search_pattern" id="rewrite_search_pattern" value="<?php echo osc_get_preference('rewrite_search_pattern'); ?>" />
                                    <br/>
                                    
                                   <label for="rewrite_contact"><?php _e('Contact') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_contact" id="rewrite_contact" value="<?php echo osc_get_preference('rewrite_contact'); ?>" />
                                    <br/>
                                   <label for="rewrite_feed"><?php _e('Feed') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_feed" id="rewrite_feed" value="<?php echo osc_get_preference('rewrite_feed'); ?>" />
                                    <br/>
                                   <label for="rewrite_language"><?php _e('Language') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_language" id="rewrite_language" value="<?php echo osc_get_preference('rewrite_language'); ?>" />
                                    <br/>
                                   <label for="rewrite_item_mark"><?php _e('Item mark') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_item_mark" id="rewrite_item_mark" value="<?php echo osc_get_preference('rewrite_item_mark'); ?>" />
                                    <br/>
                                   <label for="rewrite_item_send_friend"><?php _e('Item send friend') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_item_send_friend" id="rewrite_item_send_friend" value="<?php echo osc_get_preference('rewrite_item_send_friend'); ?>" />
                                    <br/>
                                   <label for="rewrite_item_contact"><?php _e('Item contact') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_item_contact" id="rewrite_item_contact" value="<?php echo osc_get_preference('rewrite_item_contact'); ?>" />
                                    <br/>
                                   <label for="rewrite_item_new"><?php _e('Item new') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_item_new" id="rewrite_item_new" value="<?php echo osc_get_preference('rewrite_item_new'); ?>" />
                                    <br/>
                                   <label for="rewrite_item_activate"><?php _e('Item activate') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_item_activate" id="rewrite_item_activate" value="<?php echo osc_get_preference('rewrite_item_activate'); ?>" />
                                    <br/>
                                   <label for="rewrite_item_edit"><?php _e('Item edit') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_item_edit" id="rewrite_item_edit" value="<?php echo osc_get_preference('rewrite_item_edit'); ?>" />
                                    <br/>
                                   <label for="rewrite_item_delete"><?php _e('Item delete') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_item_delete" id="rewrite_item_delete" value="<?php echo osc_get_preference('rewrite_item_delete'); ?>" />
                                    <br/>
                                   <label for="rewrite_item_resource_delete"><?php _e('Item resource delete') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_item_resource_delete" id="rewrite_item_resource_delete" value="<?php echo osc_get_preference('rewrite_item_resource_delete'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_login"><?php _e('User login') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_login" id="rewrite_user_login" value="<?php echo osc_get_preference('rewrite_user_login'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_dashboard"><?php _e('User dashboard') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_dashboard" id="rewrite_user_dashboard" value="<?php echo osc_get_preference('rewrite_user_dashboard'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_logout"><?php _e('User logout') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_logout" id="rewrite_user_logout" value="<?php echo osc_get_preference('rewrite_user_logout'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_register"><?php _e('User register') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_register" id="rewrite_user_register" value="<?php echo osc_get_preference('rewrite_user_register'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_activate"><?php _e('User activate') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_activate" id="rewrite_user_activate" value="<?php echo osc_get_preference('rewrite_user_activate'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_activate_alert"><?php _e('User activate alert') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_activate_alert" id="rewrite_user_activate_alert" value="<?php echo osc_get_preference('rewrite_user_activate_alert'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_profile"><?php _e('User profile') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_profile" id="rewrite_user_profile" value="<?php echo osc_get_preference('rewrite_user_profile'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_items"><?php _e('User items') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_items" id="rewrite_user_items" value="<?php echo osc_get_preference('rewrite_user_items'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_alerts"><?php _e('User alerts') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_alerts" id="rewrite_user_alerts" value="<?php echo osc_get_preference('rewrite_user_alerts'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_recover"><?php _e('User recover') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_recover" id="rewrite_user_recover" value="<?php echo osc_get_preference('rewrite_user_recover'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_forgot"><?php _e('User forgot') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_forgot" id="rewrite_user_forgot" value="<?php echo osc_get_preference('rewrite_user_forgot'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_change_password"><?php _e('User change password') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_change_password" id="rewrite_user_change_password" value="<?php echo osc_get_preference('rewrite_user_change_password'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_change_email"><?php _e('User change email') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_change_email" id="rewrite_user_change_email" value="<?php echo osc_get_preference('rewrite_user_change_email'); ?>" />
                                    <br/>
                                   <label for="rewrite_user_change_email_confirm"><?php _e('User change email confirm') ; ?></label>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text" name="rewrite_user_change_email_confirm" id="rewrite_user_change_email_confirm" value="<?php echo osc_get_preference('rewrite_user_change_email_confirm'); ?>" />
                                    <br/>
                                </fieldset>
                            </div>

                            <div class="actions nomargin">
                                <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                    <h3><?php _e('Useful information') ; ?></h3>
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
                    <p>
                    </p>
                </div>
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>