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
            var on = $("#moderate_comments").is(':checked');
            if(on==1) {
                $("#num_moderate_comments_div").show();
                $("#num_moderate_comments").val(0);
            } else {
                $("#num_moderate_comments_div").hide();
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
                        <img src="<?php echo osc_current_admin_theme_url('images/settings-icon.png') ; ?>" alt="" title=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Comments settings'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- settings form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">

                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="settings" />
                            <input type="hidden" name="action" value="comments_post" />
                            
                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Settings'); ?></legend>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_comments_enabled() ? 'checked="true"' : ''); ?> name="enabled_comments" id="enabled_comments" value="1" />
                                    <label for="enabled_comments"><?php _e('Comments enabled'); ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" onclick="checkbox_change();" <?php echo ((osc_moderate_comments()==-1) ? '' : 'checked="true"'); ?> name="moderate_comments" id="moderate_comments" value="1" />
                                    <label for="moderate_comments"><?php _e('Moderate comments') ; ?></label>
                                    <div name="num_moderate_comments_div" id="num_moderate_comments_div" <?php echo ((osc_moderate_comments()==-1) ? 'style="display:none"' : ''); ?>>
                                        &nbsp;<label><?php _e('Number of comments from same author that should be validated before skipping validation (0 for always moderation)'); ?></label>
                                        <input type="text" name="num_moderate_comments" id="num_moderate_comments" value="<?php echo ((osc_moderate_comments()==-1) ? '' : osc_moderate_comments()); ?>" />
                                    </div>
                                    <br/>
                                    <label><?php _e('Number of comments per page. You could limit the number of comments shown at a time at the item\'s detail page. Other comments will be available through a pagination system. (0 for show all comments at the same time)'); ?></label>
                                    <input type="text" name="comments_per_page" id="comments_per_page" value="<?php echo osc_comments_per_page(); ?>" />
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_reg_user_post_comments() ? 'checked="true"' : ''); ?> name="reg_user_post_comments" id="reg_user_post_comments" value="1" />
                                    <label for="reg_user_post_comments"><?php _e('Only allow registered users to post comments'); ?></label>
                                </fieldset>
                            </div>
                            
                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Notifications') ; ?></legend>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_notify_new_comment() ? 'checked="true"' : ''); ?> name="notify_new_comment" id="notify_new_comment" value="1" />
                                    <label for="notify_new_comment"><?php _e('Notify admin when there\'s a new comment'); ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_notify_new_comment_user() ? 'checked="true"' : ''); ?> name="notify_new_comment_user" id="notify_new_comment_user" value="1" />
                                    <label for="notify_new_comment_user"><?php _e('Notify user when there\'s a new comment'); ?></label>
                                    <br/>
                                </fieldset>
                            </div>
                            <div style="clear: both;"></div>
                            <input id="button_save" type="submit" value="<?php osc_esc_html(_e('Update')); ?>" />
                        </form>
                    </div>
                </div>
            </div> <!-- end of right column -->
        </div><!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>