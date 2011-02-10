<div class="content user_forms">
    <div class="inner">
        <h1><?php _e('Register an account (free)'); ?></h1>
        <form action="user.php" method="post" onSubmit="return checkForm()">
        <fieldset>
        	<label for="name"><?php _e('Name'); ?></label> <?php UserForm::name_text(); ?><br />
        	<label for="userName"><?php _e('User name'); ?></label> <?php UserForm::username_register_text(); ?><br />
        	<label for="password"><?php _e('Password'); ?></label> <?php UserForm::password_register_text(); ?><br />
            <label for="password"><?php _e('Re-type password'); ?></label> <?php UserForm::check_password_register_text(); ?><br />
            <p id="password-error" style="display:none;">
                <?php _e('Passwords don\'t match.'); ?>
            </p>
            <label for="email"><?php _e('E-mail'); ?></label> <?php UserForm::email_text(); ?><br />
            <?php
    		if(isset($preferences['recaptchaPubKey'])) {
                require_once 'recaptchalib.php';
                echo recaptcha_get_html($preferences['recaptchaPubKey']);
    		}
            ?>
            <input type="hidden" name="action" value="register_post" />
            <button type="submit"><?php _e('Create user'); ?></button>
            <?php osc_runHook('user_register_form'); ?>
        </fieldset>
        </form>
    </div>
</div>

<?php UserForm::js_validation(); ?>