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

    $htaccess_status = __get('htaccess');
    $file_status     = __get('file');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <script type="text/javascript">
            var base_url    = '<?php echo osc_base_url(); ?>';
            var s_close     = '<?php _e('Close'); ?>';
            var s_view_more = '<?php _e('View more'); ?>';
        </script>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <script type="text/javascript">
            $(function() {
                $("#rewrite_enabled").click(function(){
                    $("#custom_rules").toggle();
                });
            });
        </script>
        <div id="update_version" style="display:none;"></div>
		<div id="content">
            <div id="separator"></div>
			<?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
		    <div id="right_column">
				<div id="content_header" class="content_header">
					<div style="float: left;">
                        <img src="<?php echo  osc_current_admin_theme_url('images/settings-icon.png') ; ?>" alt="" title=""/>
                    </div>
					<div id="content_header_arrow">&raquo; <?php _e('Permalinks settings'); ?></div>
					<div style="clear: both;"></div>
				</div>
				<div id="content_separator"></div>
				<?php osc_show_flash_message('admin'); ?>
				<!-- settings form -->
				<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
					<div style="padding: 20px;">

                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="settings" />
                            <input type="hidden" name="action" value="permalinks_post" />
						
                            <div style="float: left; width: 100%;">
                                <fieldset>
                                    <legend><?php _e('Friendly urls'); ?></legend>
                                    <div><?php _e('By default OSClass uses web URLs which have question marks and lots of numbers in them. However, OSClass offers you friendly urls. This can improve the aesthetics, usability, and forward-compatibility of your links'); ?>.</div>
                                    <br />
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_rewrite_enabled() ? 'checked="true"' : ''); ?> name="rewrite_enabled" id="rewrite_enabled" value="1" />
                                    <label for="rewrite_enabled"><?php _e('Enable friendly urls') ; ?></label>
                                </fieldset>
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

                            <div style="clear: both;"></div>

                            <?php if(osc_rewrite_enabled()) { ?>
                            <div style="float: left; width: 100%;">
                                <fieldset>
                                    <legend><?php _e('.htaccess file'); ?></legend>
                                    <?php switch($htaccess_status) {
                                            case 1:     _e('Module <em>mod_rewrite</em> was found on the server.');
                                            break;
                                            case 2:     _e('Warning! Rewrite module wasn\'t found on the server. This means you don\'t have it enabled or you\'re running PHP as CGI (or fastCGI). In the case you don\'t have mod_rewrite you could still use friendly urls if AcceptPathInfo option is on in your Apache configuration (we can\'t know if it\'s enabled or not, but it usually is). "Index.php" will appear as a part of your URL (ie. http://www.example.com/index.php/nice/url).');
                                            break;
                                          }
                                    ?>
                                         <br/>
                                    <?php switch ($file_status) {
                                            case 3:     _e('Error. We couldn\'t write the .htaccess file on your server. Please create a file called .htaccess in the root of your OSClass installation with the following content.');
                                            break;
                                            case 1:     _e('File .htaccess already exists. Please check that the .htaccess file has the following content.');
                                            break;
                                            case 2:     _e('We\'ve created a .htaccess file on the root of your OSClass installation.');
                                            break;
                                          }
                                    ?>
                                    <div style="margin-top: 10px; clear: both;"></div>
                                    <div style="float: left; width: 50%;">
                                        <?php _e('Content of .htaccess file should look like this:'); ?>
                                        <textarea rows="8" style="width: 90%;">
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase <?php echo REL_WEB_URL; ?>

    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . <?php echo REL_WEB_URL; ?>index.php [L]
</IfModule>
                                        </textarea>
                                    </div>
                                    <div style="float: right; width: 50%;">
                                        <?php
                                            if(file_exists(ABS_PATH.'.htaccess')) {
                                                $htaccess_content = file_get_contents(osc_base_path() . '.htaccess');
                                                if($htaccess_content) {
                                                    _e('Current content of your .htaccess file:');
                                        ?>
                                        <br />
                                        <textarea rows="8" style="width: 90%;"><?php echo $htaccess_content ; ?></textarea>
                                        <?php }
                                        } ?>
                                    </div>
                                </fieldset>
                            </div>
                            <?php } ?>
                            <div style="clear: both;"></div>
                            <input id="button_save" type="submit" value="<?php _e('Update') ; ?>" />
                        </form>
					</div>
				</div>
			</div> <!-- end of right column -->
        </div>
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>