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
                $("form[name=comments_form]").validate({
                    rules: {
                        num_moderate_comments: {
                            required: true,
                            digits: true
                        },
                        comments_per_page: {
                            required: true,
                            digits: true
                        }
                    },
                    messages: {
                        num_moderate_comments: {
                            required: "<?php _e("Moderated comments: this field is required"); ?>.",
                            digits: "<?php _e("Moderated comments: this field has to be numeric only"); ?>."
                        },
                        comments_per_page: {
                            required: "<?php _e("Comments per page: this field is required"); ?>.",
                            digits: "<?php _e("Comments per page: this field has to be numeric only"); ?>."
                        }
                    },
                    wrapper: "li",
                    errorLabelContainer: "#error_list",
                    invalidHandler: function(form, validator) {
                        $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
                    }
                });

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
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="settings"><?php _e('Comments Settings') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- settings form -->
                <div class="settings comments">
                    <ul id="error_list" style="display: none;"></ul>
                    <form name="comments_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="settings" />
                        <input type="hidden" name="action" value="comments_post" />
                        <fieldset>
                            <table class="table-backoffice-form">
                                <tr>
                                    <td class="labeled"><?php _e('Default comment settings') ; ?></td>
                                    <td>
                                        <input type="checkbox" <?php echo ( osc_comments_enabled() ? 'checked="true"' : '' ) ; ?> name="enabled_comments" value="1" />
                                        <?php _e('Allow people to post comments on items') ; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="checkbox" <?php echo ( osc_reg_user_post_comments() ? 'checked="true"' : '' ) ; ?> name="reg_user_post_comments" value="1" />
                                        <?php _e('Users must be registered and logged in to comment ') ; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="checkbox" <?php echo ( ( osc_moderate_comments() == -1 ) ? '' : 'checked="true"' ) ; ?> name="moderate_comments" value="1" />
                                        <?php _e('A comment is held for moderation') ; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="additional-options">
                                        <?php printf( __('Before a comment appears, comment author must have %s previously approved comment'), '<input type="text" class="micro" name="num_moderate_comments" value="' . ( (osc_moderate_comments() == -1 ) ? '' : osc_esc_html( osc_moderate_comments() ) ) . '" />' ) ; ?>
                                        <div class="help-box"><?php _e('If the value is zero an administrator must always approve the comment') ; ?></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Other comment settings') ; ?></td>
                                    <td>
                                        <?php printf( __('Break comments into pages with %s comments per page'), '<input type="text" class="micro" name="comments_per_page" value="' . osc_esc_html( osc_comments_per_page() ) . '" />' ) ; ?>
                                        <div class="help-box"><?php _e('If the value is zero all the comments are shown' ) ; ?></div>
                                    </td>
                                </tr>
                                <tr class="separate">
                                    <td colspan="2"><h2><?php _e('Notifications') ; ?></h2></td>
                                </tr>
                                <tr>
                                    <td><?php _e('E-mail admin whenever') ?></td>
                                    <td>
                                        <input type="checkbox" <?php echo ( osc_notify_new_comment() ? 'checked="true"' : '' ) ; ?> name="notify_new_comment" value="1" />
                                        <?php _e("There is a new comment") ; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e('E-mail user whenever') ?></td>
                                    <td>
                                        <input type="checkbox" <?php echo ( osc_notify_new_comment_user() ? 'checked="true"' : '' ) ; ?> name="notify_new_comment_user" value="1" />
                                        <?php _e("There is a new comment in his item") ; ?>
                                    </td>
                                </tr>
                                <tr class="separate">
                                    <td></td>
                                    <td>
                                        <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" />
                                    </td>
                                </tr>
                            </table>
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
