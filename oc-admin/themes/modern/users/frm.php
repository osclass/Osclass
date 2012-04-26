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

    $user      = __get('user') ;
    $countries = __get('countries') ;
    $regions   = __get('regions') ;
    $cities    = __get('cities') ;
    $locales   = __get('locales') ;

    if( isset($user['pk_i_id']) ) {
        $edit       = true ;
        $title      = __('Edit user') ;
        $action_frm = 'edit_post' ;
        $btn_text   = __('Update user') ;
    } else {
        $edit       = false ;
        $title      = __('Add new user') ;
        $action_frm = 'create_post' ;
        $btn_text   = __('Add new user') ;
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
        <?php if(isset($user['pk_i_id'])) {
            UserForm::js_validation_edit() ;
        } else {
            UserForm::js_validation() ;
        }?>
        <?php UserForm::location_javascript("admin") ; ?>
        <link href="<?php echo osc_current_admin_theme_styles_url('tabs.css') ; ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('tabber-minimized.js') ; ?>"></script>
        <script type="text/javascript">
            document.write('<style type="text/css">.tabber{ display:none ; }</style>');
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
                    <h1 class="users"><?php echo $title ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- add user form -->
                <div class="settings general">
                    <ul id="error_list" style="display: none;"></ul>
                    <form name="register" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="users" />
                        <input type="hidden" name="action" value="<?php echo $action_frm ; ?>"/>
                        <?php UserForm::primary_input_hidden($user) ; ?>
                        <?php if( $edit ) { ?>
                            <input type="hidden" name="b_enabled" value="<?php echo $user['b_enabled'] ; ?>" />
                            <input type="hidden" name="b_active" value="<?php echo $user['b_active'] ; ?>" />
                        <?php } ?>
                        <fieldset>
                            <h3><?php _e('Contact info') ; ?></h3>
                            <div class="input-line">
                                <label><?php _e('Name') ; ?></label>
                                <div class="input large">
                                    <?php UserForm::name_text($user) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('E-mail') ; ?> <em><?php _e('(required)') ; ?></em></label>
                                <div class="input large">
                                    <?php UserForm::email_text($user) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Cell phone') ; ?></label>
                                <div class="input medium">
                                    <?php UserForm::mobile_text($user) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Phone') ; ?></label>
                                <div class="input medium">
                                    <?php UserForm::phone_land_text($user) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Website') ; ?></label>
                                <div class="input medium">
                                    <?php UserForm::website_text($user) ; ?>
                                </div>
                            </div>
                            <h3><?php _e('About yourself') ; ?></h3>
                            <div class="input-line">
                                <label><?php _e('User type') ; ?></label>
                                <div class="input">
                                    <?php UserForm::is_company_select($user) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Additional information') ; ?></label>
                                <div class="input">
                                    <?php UserForm::multilanguage_info($locales, $user) ; ?>
                                </div>
                            </div>
                            <h3><?php _e('Location') ; ?></h3>
                            <div class="input-line">
                                <label><?php _e('Country') ; ?></label>
                                <div class="input">
                                    <?php UserForm::country_select($countries, $user) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Region') ; ?></label>
                                <div class="input">
                                    <?php UserForm::region_select($regions, $user) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('City') ; ?></label>
                                <div class="input">
                                    <?php UserForm::city_select($cities, $user) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('City area') ; ?></label>
                                <div class="input medium">
                                    <?php UserForm::city_area_text($user) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Address') ; ?></label>
                                <div class="input medium">
                                    <?php UserForm::address_text($user) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('New password') ; ?> <?php if( !$edit ) { printf('<em>%s</em>', __('(twice, required)')) ; } ?></label>
                                <div class="input medium">
                                    <?php UserForm::password_text($user) ; ?>
                                    <?php if( $edit ) { ?>
                                    <p class="help-inline"><?php _e('If you would like to change the password type a new one. Otherwise leave this blank') ; ?></p>
                                    <?php } ?>
                                </div>
                                <div class="input medium">
                                    <?php UserForm::check_password_text($user) ; ?>
                                    <?php if( $edit ) { ?>
                                    <p class="help-inline"><?php _e('Type your new password again') ; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="actions">
                                <input type="submit" value="<?php echo osc_esc_html($btn_text) ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /add user form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>
