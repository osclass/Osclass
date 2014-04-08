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

    bender_add_body_class('login');
    osc_current_web_theme_path('header.php');
?>
<div class="form-container form-horizontal form-container-box">
    <div class="header">
        <h1><?php _e('Access to your account', 'bender'); ?></h1>
    </div>
    <div class="resp-wrapper">
        <form action="<?php echo osc_base_url(true); ?>" method="post" >
            <input type="hidden" name="page" value="login" />
            <input type="hidden" name="action" value="login_post" />

            <div class="control-group">
                <label class="control-label" for="email"><?php _e('E-mail', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::email_login_text(); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="password"><?php _e('Password', 'bender'); ?></label>
                <div class="controls">
                    <?php UserForm::password_login_text(); ?>
                </div>
            </div>
            <div class="control-group">
                <div class="controls checkbox">
                    <?php UserForm::rememberme_login_checkbox();?> <label for="remember"><?php _e('Remember me', 'bender'); ?></label>
                </div>
                <div class="controls">
                    <button type="submit" class="ui-button ui-button-middle ui-button-main"><?php _e("Log in", 'bender');?></button>
                </div>
            </div>
            <div class="actions">
                <a href="<?php echo osc_register_account_url(); ?>"><?php _e("Register for a free account", 'bender'); ?></a><br /><a href="<?php echo osc_recover_user_password_url(); ?>"><?php _e("Forgot password?", 'bender'); ?></a>
            </div>
        </form>
    </div>
</div>
<?php osc_current_web_theme_path('footer.php') ; ?>