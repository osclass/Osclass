<?php

/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

?>

<?php defined('ABS_PATH') or die( __('Invalid OSClass request.') ); ?>

<?php
    $fields = array(
        array('name' => 's_name', 'error_msg' => __('You have to write a name.'))
        ,array('name' => 's_email', 'error_msg' => __('You have to write an e-mail.'))
        ,array('name' => 's_username', 'error_msg' => __('You have to write an username.'))
        ,array('name' => 's_password', 'error_msg' => __('You have to write a password.'))
    );
    osc_check_form_js($fields);
?>

<div id="content">
    <div id="separator"></div>

    <?php include_once osc_current_admin_theme_path() . '/include/backoffice_menu.php'; ?>

    <div id="right_column">
        <div id="content_header" class="content_header">
            <div style="float: left;"><img src="<?php echo  osc_current_admin_theme_url() ; ?>/images/back_office/admin-icon.png" /></div>
            <div id="content_header_arrow">&raquo; <?php _e('Add new administrator'); ?></div>
            <div style="clear: both;"></div>
        </div>

        <div id="content_separator"></div>

        <?php osc_show_flash_messages() ; ?>

        <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
            <div style="padding: 20px;">

                <form action="admins.php" method="post" onSubmit="return checkForm()">
                    <input type="hidden" name="action" value="add_post" />

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Real name') ; ?> (<?php _e('required') ; ?>)</legend>
                            <input type="text" name="s_name" id="s_name" />
                        </fieldset>
                    </div>

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('E-mail'); ?></legend>
                            <input type="text" name="s_email" id="s_email" />
                        </fieldset>
                    </div>
                    <div style="clear: both;"></div>

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('User name'); ?> (<?php _e('required'); ?>)</legend>
                            <input type="text" name="s_username" id="s_username" />
                        </fieldset>
                    </div>

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Password'); ?> (<?php _e('required'); ?>)</legend>
                            <input type="password" name="s_password" id="s_password" />
                        </fieldset>
                    </div>
                    <div style="clear: both;"></div>
                    <input id="button_save" type="submit" value="<?php _e('Create administrator'); ?>" />
                </form>

            </div>
        </div>
    </div>
</div>
