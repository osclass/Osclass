<?php
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    // meta tag robots
    osc_add_hook('header','bender_nofollow_construct');

    bender_add_body_class('user user-profile');
    osc_add_hook('before-main','sidebar');
    function sidebar(){
        osc_current_web_theme_path('user-sidebar.php');
    }
    osc_add_filter('meta_title_filter','custom_meta_title');
    function custom_meta_title($data){
        return __('Update account', 'bender');
    }
    osc_current_web_theme_path('header.php') ;
    $osc_user = osc_user();
?>
<h1><?php _e('Update account', 'bender'); ?></h1>
<?php UserForm::location_javascript(); ?>
<div class="form-container form-horizontal">
    <div class="resp-wrapper">
        <ul id="error_list"></ul>
        <form action="<?php echo osc_base_url(true); ?>" method="post">
            <input type="hidden" name="page" value="user" />
            <input type="hidden" name="action" value="profile_post" />
            <div class="control-group">
                <label class="control-label" for="name"><?php _e('Name', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::name_text(osc_user()); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="user_type"><?php _e('User type', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::is_company_select(osc_user()); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="phoneMobile"><?php _e('Cell phone', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::mobile_text(osc_user()); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="phoneLand"><?php _e('Phone', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::phone_land_text(osc_user()); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="country"><?php _e('Country', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::country_select(osc_get_countries(), osc_user()); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="region"><?php _e('Region', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::region_select(osc_get_regions(), osc_user()); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="city"><?php _e('City', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::city_select(osc_get_cities(), osc_user()); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="city_area"><?php _e('City area', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::city_area_text(osc_user()); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"l for="address"><?php _e('Address', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::address_text(osc_user()); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="webSite"><?php _e('Website', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::website_text(osc_user()); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="s_info"><?php _e('Description', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::info_textarea('s_info', osc_locale_code(), @$osc_user['locale'][osc_locale_code()]['s_info']); ?>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="ui-button ui-button-middle ui-button-main"><?php _e("Update", 'bender');?></button>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <?php osc_run_hook('user_form', osc_user()); ?>
                </div>
            </div>
        </form>
    </div>
</div>
<?php osc_current_web_theme_path('footer.php') ; ?>