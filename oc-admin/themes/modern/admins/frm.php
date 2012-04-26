<?php
    /**
     * OSClass - software for creating and publishing online classified advertising platforms
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

    //getting variables for this view
    $admin = __get("admin") ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
    </head>
    <body>
        <?php 
            if(isset($admin['pk_i_id'])) {
                // Editing an admin
                $admin_edit = true;
                $title = __("Edit admin") ;
                $action_frm = "edit_post";
                $btn_text = __("Save");
            } else {
                // Adding new admin
                $admin_edit = false;
                $title = __("Add new admin") ;
                $action_frm = "add_post";
                $btn_text = __("Add");
            }
        ?>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="admins"><?php echo $title; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- add admin form -->
                <div class="settings general">
                    <ul id="error_list" style="display: none;"></ul>
                    <form name="admin_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="action" value="<?php echo $action_frm; ?>" />
                        <input type="hidden" name="page" value="admins" />
                        <?php AdminForm::primary_input_hidden($admin); ?>
                        <?php AdminForm::js_validation(); ?>
                        <fieldset>
                            <div class="input-line">
                                <label><?php _e('Name <em>(required)</em>') ; ?></label>
                                <div class="input">
                                    <?php AdminForm::name_text($admin) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Username <em>(required)</em>') ; ?></label>
                                <div class="input">
                                    <?php AdminForm::username_text($admin) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('E-mail <em>(required)</em>') ; ?></label>
                                <div class="input">
                                    <?php AdminForm::email_text($admin) ; ?>
                                </div>
                            </div>
                            <?php if($admin_edit) { ?>
                                <div class="input-line">
                                    <label><?php _e('Current password') ; ?></label>
                                    <div class="input">
                                        <?php AdminForm::old_password_text($admin) ; ?>
                                        <p class="help-inline"><em><?php _e('If you would like to change the password type a new one. Otherwise leave this blank') ; ?></em></p>
                                    </div>
                                </div>
                            <?php }; ?>
                            <div class="input-line">
                                <label><?php _e('New password') ; ?></label>
                                <div class="input">
                                    <?php AdminForm::password_text($admin) ; ?>
                                </div>
                                <?php if($admin_edit) { ?>
                                    <div class="input">
                                        <?php AdminForm::check_password_text($admin) ; ?>
                                        <p class="help-inline"><em><?php _e('Type your new password again') ; ?></em></p>
                                    </div>
                                <?php }; ?>
                            </div>
                            <div class="actions">
                                <input type="submit" value="<?php echo osc_esc_html($btn_text) ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /add admin form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>