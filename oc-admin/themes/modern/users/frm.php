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
    
    function customFrmText(){
        $user      = __get('user') ;
        $return = array();

        if( isset($user['pk_i_id']) ) {
            $return['edit']       = true ;
            $return['title']      = __('Edit user') ;
            $return['action_frm'] = 'edit_post' ;
            $return['btn_text']   = __('Update user') ;
            $return['alerts'] = Alerts::newInstance()->findByUser($user['pk_i_id']);
        } else {
            $return['edit']       = false ;
            $return['title']      = __('Add new user') ;
            $return['action_frm'] = 'create_post' ;
            $return['btn_text']   = __('Add new user') ;
            $return['alerts'] = array();
        }
        return $return;
    }
    function customPageHeader(){ ?>
        <h1><?php _e('Users'); ?></h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        $aux = customFrmText();
        return sprintf('%s &raquo; %s', $aux['title'], $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    //customize Head
    function customHead() { 
        $user = __get('user');
        ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
        <?php if(isset($user['pk_i_id'])) {
            UserForm::js_validation_edit() ;
        } else {
            UserForm::js_validation() ;
        }?>
        <?php UserForm::location_javascript("admin") ; ?>

        <?php
    }
    osc_add_hook('admin_header','customHead');
    
    $aux    = customFrmText();
?>
<?php osc_current_admin_theme_path('parts/header.php') ; ?>
<script type="text/javascript">
    function delete_alert(pk_i_id) {
        $("#dialog-alert-delete input[name='alert_id']").attr('value', pk_i_id);
        $("#dialog-alert-delete").dialog('open');
    }
    
    $(document).ready(function(){
        /*$("#alert-delete-submit").live("click", function(){
            $("#dialog-alert-delete").dialog('close');
        });*/
    }
</script>
<h2 class="render-title"><?php echo $aux['title'] ; ?></h3>


    <!-- add user form -->
    <div class="settings-user">
        <ul id="error_list"></ul>
        <form name="register" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
            <input type="hidden" name="page" value="users" />
            <input type="hidden" name="action" value="<?php echo $aux['action_frm'] ; ?>" />
            <h3 class="render-title"><?php _e('Contact info') ; ?></h3>
            <?php UserForm::primary_input_hidden($user) ; ?>
            <?php if($aux['edit']) { ?>
                <input type="hidden" name="b_enabled" value="<?php echo $user['b_enabled'] ; ?>" />
                <input type="hidden" name="b_active" value="<?php echo $user['b_active'] ; ?>" />
            <?php } ?>
            <fieldset>
            <div class="form-horizontal">
                <?php if($aux['edit']) { ?>
                <div class="form-row">
                    <div class="form-label"><?php _e('Last access') ; ?></div>
                    <div class="form-controls">
                        <div class='form-label-checkbox'>
                        <?php echo sprintf(__("%s on %s"), $user['s_access_ip'], $user['dt_access_date']);?>
                        </div>
                    </div>
                </div>
                <?php }; ?>
                <div class="form-row">
                    <div class="form-label"><?php _e('Name') ; ?></div>
                    <div class="form-controls">
                        <?php UserForm::name_text($user) ; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('E-mail') ; ?> <em><?php _e('(required)') ; ?></em></div>
                    <div class="form-controls">
                        <?php UserForm::email_text($user) ; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Cell phone') ; ?></div>
                    <div class="form-controls">
                        <?php UserForm::mobile_text($user) ; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-label"><?php _e('Phone') ; ?></div>
                    <div class="form-controls">
                        <?php UserForm::mobile_text($user) ; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Cell phone') ; ?></div>
                    <div class="form-controls">
                        <?php UserForm::phone_land_text($user) ; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Website') ; ?></div>
                    <div class="form-controls">
                        <?php UserForm::website_text($user) ; ?>
                    </div>
                </div>
                <h3 class="render-title"><?php _e('About you') ; ?></h3>
                <div class="form-row">
                    <div class="form-label"><?php _e('User type') ; ?></div>
                    <div class="form-controls">
                        <?php UserForm::is_company_select($user) ; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Additional information') ; ?></div>
                    <div class="form-controls">
                        <?php UserForm::multilanguage_info($locales, $user) ; ?>
                    </div>
                </div>
                <h3 class="render-title"><?php _e('Location') ; ?></h3>
                <div class="form-row">
                    <div class="form-label"><?php _e('Country') ; ?></div>
                    <div class="form-controls">
                        <?php UserForm::country_select($countries, $user) ; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Region') ; ?></div>
                    <div class="form-controls">
                        <?php UserForm::region_select($regions, $user) ; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('City') ; ?></div>
                    <div class="form-controls">
                        <?php UserForm::city_select($cities, $user) ; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('City area') ; ?></div>
                    <div class="form-controls">
                        <?php UserForm::city_area_text($user) ; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Address') ; ?></div>
                    <div class="form-controls">
                        <?php UserForm::address_text($user) ; ?>
                    </div>
                </div>
                <h3 class="render-title"><?php _e('Password') ; ?></h3>
                <div class="form-row">
                    <div class="form-label"><?php _e('New password') ; ?><?php if(!$aux['edit']) { printf('<em>%s</em>', __('(twice, required)')) ; } ?></div>
                    <div class="form-controls">
                        <?php UserForm::password_text($user) ; ?>
                        <?php if($aux['edit']) { ?>
                            <p class="help-inline"><?php _e("If you'd like to change the password, type a new one. Otherwise leave this blank") ; ?></p>
                        <?php } ?>
                        <div class="input-separate-top">
                            <?php UserForm::check_password_text($user) ; ?>
                            <?php if($aux['edit']) { ?>
                                <p class="help-inline"><?php _e('Type your new password again') ; ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="form-actions">
                    <input type="submit" value="<?php echo osc_esc_html($aux['btn_text']) ; ?>" class="btn btn-submit" />
                </div>
            </div>
            </fieldset>
        </form>
    </div>
    
    
    <?php if($aux['edit'] && count($aux['alerts'])>0) { ?>
        <div class="settings-user">
            <ul id="error_list"></ul>
            <form>
                <div class="form-horizontal">
                    <h3 class="render-title"><?php _e('Alerts') ; ?></h3>
                    <div class="form-row">
                        <?php for($k=0;$k<count($aux['alerts']);$k++) { 
                            $array_conditions = (array)json_decode(base64_decode($aux['alerts'][$k]['s_search']), true);
                            $new_search = Search::newInstance();
                            $new_search->setJsonAlert($array_conditions);
                            $new_search->limit(0, 2);
                            $results = $new_search->doSearch();
                            ?>
                            <div class="form-label">
                                <?php echo sprintf(__('Alert #%d'), ($k+1)) ; ?>
                                <br/>
                                <a href="javascript:delete_alert(<?php echo $aux['alerts'][$k]['pk_i_id']; ?>);" ><?php _e("Delete"); ?></a>
                             </div>
                            <div class="form-controls">
                                <?php foreach($results as $r) { ?>
                                <label><b><?php echo $r['s_title']; ?></b></label>
                                <p><?php echo $r['s_description']; ?></p>
                                <?php }; ?>
                           </div>
                        <?php }; ?>
                    </div>
                    <div class="clear"></div>
                </div>
                </fieldset>
            </form>
        </div>
    <?php }; ?>
    
    
    <form id="dialog-alert-delete" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Delete alert')); ?>">
        <input type="hidden" name="alert_id" value="" />
        <div class="form-horizontal">
            <div class="form-row">
                <?php _e('Are you sure you want to delete this alert?'); ?>
            </div>
            <div class="form-actions">
                <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#dialog-user-delete').dialog('close');"><?php _e('Cancel'); ?></a>
                <input id="alert-delete-submit" type="submit" value="<?php echo osc_esc_html( __('Delete') ); ?>" class="btn btn-red" />
                </div>
            </div>
        </div>
    </form>    
    <!-- /add user form -->
<?php osc_current_admin_theme_path('parts/footer.php') ; ?>