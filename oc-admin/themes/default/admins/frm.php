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
    $admin = __get("admin") ;
    function customFrmText(){
        $admin = __get("admin") ;
        $return = array();
        if( isset($admin['pk_i_id']) ) {
            $return['admin_edit']       = true ;
            $return['title']      = __('Edit admin') ;
            $return['action_frm'] = 'edit_post' ;
            $return['btn_text']   = __('Save') ;
        } else {
            $return['admin_edit']       = false ;
            $return['title']      = __('Add new admin') ;
            $return['action_frm'] = 'add_post' ;
            $return['btn_text']   = __('Add') ;
        }
        return $return;
    }
    function customPageHeader(){ ?>
        <h1><?php _e('Users'); ?></h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');
    //customize Head
    function customHead() { ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
        <?php
    }
    osc_add_hook('admin_header','customHead');
    $new_item   = __get('new_item') ;
    $actions    = __get('actions') ;
?>
<?php osc_current_admin_theme_path('parts/header.php') ; ?>
<h2 class="render-title"><?php echo customFrmText()['title'] ; ?></h3>
    <!-- add user form -->
    <div class="settings-user">
        <ul id="error_list" style="display: none;"></ul>
        <form name="admin_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
            <input type="hidden" name="action" value="<?php echo customFrmText()['action_frm'] ; ?>" />
            <input type="hidden" name="page" value="admins" />
            <?php AdminForm::primary_input_hidden($admin); ?>
            <?php AdminForm::js_validation(); ?>




            <fieldset>
            <div class="form-horizontal">
                <div class="form-row">
                    <div class="form-label"><?php _e('Name <em>(required)</em>') ; ?></div>
                    <div class="form-controls">
                        <?php AdminForm::name_text($admin) ; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Username <em>(required)</em>') ; ?></div>
                    <div class="form-controls"><?php AdminForm::username_text($admin) ; ?></div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('E-mail <em>(required)</em>') ; ?></div>
                    <div class="form-controls"><?php AdminForm::email_text($admin) ; ?></div>
                </div>
                <?php if(!customFrmText()['admin_edit'] || (customFrmText()['admin_edit'] && Params::getParam('id')!= osc_logged_admin_id() && Params::getParam('id')!='')) { ?>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Admin type <em>(required)</em>') ; ?></div>
                        <div class="form-controls">
                            <?php AdminForm::type_select($admin) ; ?>
                            <p class="help-inline"><em><?php _e('Administrators have full control over all aspects of your installation, while moderators are only allowed to moderate listing, comments and media files') ; ?></em></p>
                        </div>
                    </div>
                <?php }; ?>
                <?php if(customFrmText()['admin_edit'] && osc_logged_admin_id()==$admin['pk_i_id']) { ?>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Current password') ; ?></div>
                        <div class="form-controls">
                            <?php AdminForm::old_password_text($admin) ; ?>
                            <p class="help-inline"><em><?php _e('If you would like to change the password type your current one. Otherwise leave this blank') ; ?></em></p>
                        </div>
                    </div>
                <?php }; ?>
                <div class="form-row">
                    <div class="form-label"><?php _e('New password') ; ?></div>
                    <div class="form-controls">
                        <?php AdminForm::password_text($admin) ; ?>
                    </div>
                    <?php if(customFrmText()['admin_edit']) { ?>
                        <div class="input-separate-top">
                            <?php AdminForm::check_password_text($admin) ; ?>
                            <p class="help-inline"><em><?php _e('Type your new password again') ; ?></em></p>
                        </div>
                    <?php }; ?>
                </div>
                <div class="clear"></div>



                <div class="form-actions">
                    <input type="submit" value="<?php echo osc_esc_html(customFrmText()['btn_text']) ; ?>" class="btn btn-submit" />
                </div>
            </div>
            </fieldset>
        </form>
    </div>
    <!-- /add user form -->
<?php osc_current_admin_theme_path('parts/footer.php') ; ?>