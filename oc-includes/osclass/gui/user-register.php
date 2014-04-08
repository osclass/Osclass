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

    bender_add_body_class('register');
    osc_enqueue_script('jquery-validate');
    osc_current_web_theme_path('header.php') ;
?>
<div class="form-container form-horizontal form-container-box">
    <div class="header">
        <h1><?php _e('Register an account for free', 'bender'); ?></h1>
    </div>
    <div class="resp-wrapper">
        <form name="register" action="<?php echo osc_base_url(true); ?>" method="post" >
            <input type="hidden" name="page" value="register" />
            <input type="hidden" name="action" value="register_post" />
            <ul id="error_list"></ul>
            <div class="control-group">
                <label class="control-label" for="name"><?php _e('Name', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::name_text(); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="email"><?php _e('E-mail', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::email_text(); ?>
                    <?php osc_show_recaptcha('register'); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="password"><?php _e('Password', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::password_text(); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="password-2"><?php _e('Repeat password', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::check_password_text(); ?>
                    <p id="password-error" style="display:none;">
                        <?php _e("Passwords don't match", 'bender'); ?>
                    </p>
                </div>
            </div>
            <?php osc_run_hook('user_register_form'); ?>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="ui-button ui-button-middle ui-button-main"><?php _e("Create", 'bender'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php UserForm::js_validation(); ?>
<?php osc_current_web_theme_path('footer.php') ; ?>