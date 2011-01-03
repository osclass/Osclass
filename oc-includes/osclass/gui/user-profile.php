<div id="home_header"><div><?php _e('Update your profile'); ?></div></div>
<form action="<?php echo osc_createURL('user');?>" method="post">
<input type="hidden" name="action" value="profile_post" />

<div align="center">
	<div id="register_form" style="width: 400px; margin-bottom: 20px;">
		<p>
		<label for="name"><?php _e('Name'); ?></label><br />
        <?php UserForm::name_text($user); ?>
		</p>
		
		<p>
		<label for="userName"><?php _e('User name'); ?></label><br />
        <?php UserForm::username_text($user); ?>
		</p>
		
		<p>
		<label for="password"><?php _e('Password'); ?></label><br />
        <?php UserForm::password_text($user); ?><br />
		<span style="font-size: 10px; "><?php _e('Leave it empty if you don\'t want to change it now.'); ?></span>
		</p>
		
		<p>
		<label for="password2"><?php _e('Retype the password'); ?></label><br />
        <?php UserForm::check_password_text($user); ?>
		</p>
		
		<p>
		<label for="email"><?php _e('E-mail'); ?></label><br />
        <?php UserForm::email_text($user); ?>
		</p>
		
		<p>
		<label for="webSite"><?php _e('Web site'); ?></label><br />
        <?php UserForm::website_text($user); ?>
		</p>
		
		<p>
		<label for="info"><?php _e('Additional information'); ?></label><br />
        <?php UserForm::info_textarea($user); ?>
		</p>
		
		<p>
		<label for="phoneMobile"><?php _e('Mobile phone'); ?></label><br />
        <?php UserForm::mobile_text($user); ?>
		</p>
		
		<p>
		<label for="phoneLand"><?php _e('Land phone'); ?></label><br />
        <?php UserForm::phone_land_text($user); ?>
		</p>
		
		<p>
			<button type="submit"><?php _e('Update profile'); ?></button>
		</p>
		<?php osc_runHook('user_form'); ?>
	</div>
</div>
</form>
