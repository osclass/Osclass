    <div class="modify_profile">
        <h2><?php _e('Update your profile'); ?></h2>

        <form action="user.php" method="post">
        <fieldset>
        	<label for="name"><?php _e('Name'); ?></label>
            <?php UserForm::name_text($user); ?><br />

        	<label for="userName"><?php _e('User name'); ?></label>
            <?php UserForm::username_text($user); ?><br />

        	<label for="password"><?php _e('Password'); ?></label>
            <?php UserForm::password_text($user); ?><br />
        	<span style="font-size: 10px; "><?php _e('Leave it empty if you don\'t want to change it now.'); ?></span><br />

        	<label for="password2"><?php _e('Retype the password'); ?></label>
            <?php UserForm::check_password_text($user); ?><br />

        	<label for="email"><?php _e('E-mail'); ?></label>
            <?php UserForm::email_text($user); ?><br />

        	<label for="webSite"><?php _e('Web site'); ?></label>
            <?php UserForm::website_text($user); ?><br />

        	<label for="info"><?php _e('Additional information'); ?></label>
            <?php UserForm::info_textarea($user); ?><br />

        	<label for="phoneMobile"><?php _e('Mobile phone'); ?></label>
            <?php UserForm::mobile_text($user); ?><br />

        	<label for="phoneLand"><?php _e('Land phone'); ?></label>
            <?php UserForm::phone_land_text($user); ?><br />

        	<button type="submit"><?php _e('Update profile'); ?></button>

        	<?php osc_runHook('user_form'); ?>
    
            <input type="hidden" name="action" value="profile_post" />
        </fieldset>
        </form>
    </div>

    <!-- Close .content & #main open at user-menu.php -->
    </div>
</div>