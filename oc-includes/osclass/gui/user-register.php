<div id="home_header">
    <div>
        <?php _e('Register an account (free)'); ?>
    </div>
</div>
<?php UserForm::js_validation(); ?>
<div align="center">
    <div id="register_form">
        <form action="<?php echo osc_createURL('user');?>" method="post" onSubmit="return checkForm()">
            <input type="hidden" name="action" value="register_post" />
            <p>
		<label for="name"><?php _e('Name'); ?></label><br />
                <?php UserForm::name_text(); ?>
            </p>
            <p>
                <label for="email"><?php _e('E-mail'); ?></label><br />
                <?php UserForm::email_text(); ?>
            </p>
            <p>
		<label for="password"><?php _e('Password'); ?></label><br />
                <?php UserForm::password_register_text(); ?>
            </p>
            <p>
		<label for="password"><?php _e('Re-type password'); ?></label><br />
                <?php UserForm::check_password_register_text(); ?>
            </p>
            <p id="password-error" style="display:none;">
                <?php _e('Passwords don\'t match.'); ?>
            </p>
            <?php
		if(isset($preferences['recaptchaPubKey'])) {
                    require_once LIB_PATH . 'recaptchalib.php';
                    echo recaptcha_get_html($preferences['recaptchaPubKey']);
		}
            ?>
            <p>
                <button type="submit"><?php _e('Create user'); ?></button>
            </p>
            <?php osc_runHook('user_register_form'); ?>
        </form>
    </div>
</div>

