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
                if( !$('input[name="moderate_comments"]').is(':checked') ) {
                    $('.comments_approved').css('display', 'none') ;
                }

                $('input[name="moderate_comments"]').bind('change', function() {
                    if( $(this).is(':checked') ) {
                        $('.comments_approved').css('display', '') ;
                    } else {
                        $('.comments_approved').css('display', 'none') ;
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
                    <h1 class="settings"><?php _e('Comments Settings') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
                <!-- settings form -->
                <div class="settings comments">
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="settings" />
                        <input type="hidden" name="action" value="comments_post" />
                        <fieldset>
                            <div class="input-line">
                                <label><?php _e('Default comment settings') ; ?></label>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_comments_enabled() ? 'checked="true"' : '' ) ; ?> name="enabled_comments" value="1" />
                                        <?php _e('Allow people to post comments on items') ; ?>
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_reg_user_post_comments() ? 'checked="true"' : '' ) ; ?> name="reg_user_post_comments" value="1" />
                                        <?php _e('Users must be registered and logged in to comment ') ; ?>
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( ( osc_moderate_comments() == -1 ) ? '' : 'checked="true"' ) ; ?> name="moderate_comments" value="1" />
                                        <?php _e('A comment is held for moderation') ; ?>
                                    </label>
                                </div>
                                <div class="input-line comments_approved">
                                    <div class="input">
                                        Before a comment appears, comment author must have <input type="text" class="micro" name="num_moderate_comments" value="<?php echo ( (osc_moderate_comments() == -1 ) ? '' : osc_moderate_comments() ) ; ?>" /> previously approved comment
                                        <p class="help"><?php _e('If the value is zero an administrator must always approve the comment') ; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Other comment settings') ; ?></label>
                                <div class="input">
                                    Break comments into pages with <input type="text" class="micro" name="comments_per_page" value="<?php echo osc_comments_per_page(); ?>" /> comments per page
                                    <p class="help">If the value is zero all the comments are showed</p>
                                </div>
                            </div>
                            <h3>Notifications</h3>
                            <div class="input-line">
                                <label><?php _e('E-mail admin whenever') ?></label>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_notify_new_comment() ? 'checked="true"' : '' ) ; ?> name="notify_new_comment" value="1" />
                                        <?php _e("There is a new comment") ; ?>
                                    </label>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('E-mail user whenever') ?></label>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_notify_new_comment_user() ? 'checked="true"' : '' ) ; ?> name="notify_new_comment_user" value="1" />
                                        <?php _e("There is a new comment in his item") ; ?>
                                    </label>
                                </div>
                            </div>
                            <div class="actions">
                                <input type="submit" value="<?php osc_esc_html( _e('Save changes') ) ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /settings form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>