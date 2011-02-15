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

    $user = $this->_get("user");
    $countries = $this->_get("countries");
    $regions = $this->_get("regions");
    $cities = $this->_get("cities");
    $locales = $this->_get("locales");

    if(isset($user['pk_i_id'])) {
        //editing...
        $edit = true ;
        $title = __("Edit user") ;
        $action_frm = "edit_post";
        $btn_text = __("Save");
    } else {
        //adding...
        $edit = false ;
        $title = __("Add user");
        $action_frm = "create_post";
        $btn_text = __('Add');
    }
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php $this->osc_print_head() ; ?>
        <?php if(isset($user['pk_i_id'])) {
            UserForm::js_validation_edit() ;
        } else {
            UserForm::js_validation() ;
        }?>
    </head>
    <body>
        <?php $this->osc_print_header() ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header"><?php _e('Users'); ?></div>
            <div id="content">
                <div id="separator"></div>

                <?php UserForm::location_javascript("admin"); ?>

                <?php include_once osc_current_admin_theme_path() . 'include/backoffice_menu.php' ; ?>

                <div id="right_column">
                    <div id="content_header" class="content_header">
                        <div style="float: left;"><img src="<?php echo osc_current_admin_theme_url() ; ?>images/back_office/user-group-icon.png" /></div>
                        <div id="content_header_arrow">&raquo; <?php echo $title ; ?></div>
                        <div style="clear: both;"></div>
                    </div>
				
                    <div id="content_separator"></div>
                    <?php osc_show_flash_message() ; ?>
				
                    <!-- add new item form -->
                    <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                        <div style="padding: 20px;">

                            <form action="<?php echo osc_admin_base_url(true); ?>?page=users" method="post" onSubmit="return checkForm()">
                            <input type="hidden" name="action" value="<?php echo $action_frm;?>"/>
                            <?php UserForm::primary_input_hidden($user); ?>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('E-mail'); ?></legend>
                                    <?php UserForm::email_text($user); ?>
                                </fieldset>
                                <fieldset>
                                    <legend><?php _e('Activation'); ?></legend>
                                    <input type="checkbox" name="b_enabled" id="b_enabled" value="1" <?php if($user['b_enabled']==1) { echo 'checked'; };?>/> <label for="b_enabled"><?php _e('Active user'); ?></label>
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Password'); ?></legend>
                                    <?php UserForm::password_register_text($user); ?>
                                </fieldset>
                                <fieldset>
                                    <legend><?php _e('Retype the password'); ?> </legend>
                                    <?php UserForm::check_password_register_text($user); ?>
                                </fieldset>
                                <p id="password-error" style="display:none;">
                                    <?php _e('Passwords don\'t match.'); ?>
                                </p>
                            </div>

                            <div style="clear: both;"></div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Real name'); ?> (<?php _e('required'); ?>)</legend>
                                    <?php UserForm::name_text($user); ?>
                                </fieldset>
                                <fieldset>
                                    <legend><?php _e('Mobile phone'); ?></legend>
                                    <?php UserForm::mobile_text($user); ?>
                                </fieldset>
                                <fieldset>
                                    <legend><?php _e('Land phone'); ?></legend>
                                    <?php UserForm::phone_land_text($user); ?>
                                </fieldset>
                                <fieldset>
                                    <legend><?php _e('Web site'); ?></legend>
                                    <?php UserForm::website_text($user); ?>
                                </fieldset>
                                <fieldset style="min-height: 166px;">
                                    <legend><?php _e('Additional information'); ?></legend>
                                    <?php UserForm::multilanguage_info($locales, $user); ?>
                                </fieldset>
                            </div>
                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Country'); ?></legend>
                                    <?php UserForm::country_select($countries, $user); ?>
                                </fieldset>
                                <fieldset>
                                    <legend><?php _e('Region'); ?></legend>
                                    <?php UserForm::region_select($regions, $user); ?>
                                </fieldset>
                                <fieldset>
                                    <legend><?php _e('City'); ?></legend>
                                    <?php UserForm::city_select($cities, $user); ?>
                                </fieldset>
                                <fieldset>
                                    <legend><?php _e('City Area'); ?></legend>
                                    <?php UserForm::city_area_text($user); ?>
                                </fieldset>
                                <fieldset>
                                    <legend><?php _e('Address'); ?></legend>
                                    <?php UserForm::address_text($user); ?>
                                </fieldset>
                            </div>

                            <div style="clear: both;"></div>

                            <input id="button_save" type="submit" value="<?php echo $btn_text; ?>" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <?php $this->osc_print_footer() ; ?>
    </body>
</html>				            
