<div id="home_header"><div><?php _e('Update your profile'); ?></div></div>
<form action="<?php echo osc_createURL('user');?>" method="post">
<?php UserForm::location_javascript(); ?>
<?php UserForm::js_validation(); ?>
<input type="hidden" name="action" value="profile_post" onSubmit="return checkForm()"/>

<div align="center">
	<div id="register_form" style="width: 400px; margin-bottom: 20px;">
		<p>
		<label for="name"><?php _e('Name'); ?></label><br />
        <?php UserForm::name_text($user); ?>
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
		<p id="password-error" style="display:none;">
                <?php _e('Passwords don\'t match.'); ?>
            </p>
		<p>
		<label for="email"><?php _e('E-mail'); ?></label><br />
        <?php echo $user['s_email']; ?>
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
		<label for="country"><?php _e('Country'); ?></label><br />
        <?php UserForm::country_select($countries, $user); ?>
		</p>
		
		<p>
		<label for="region"><?php _e('Region'); ?></label><br />
        <?php UserForm::region_select($regions, $user); ?>
		</p>
		
		<p>
		<label for="city"><?php _e('City'); ?></label><br />
        <?php UserForm::city_select($cities, $user); ?>
		</p>
		
		<p>
		<label for="city_area"><?php _e('City Area'); ?></label><br />
        <?php UserForm::city_area_text($user); ?>
		</p>
		
		<p>
		<label for="address"><?php _e('Address'); ?></label><br />
        <?php UserForm::address_text($user); ?>
		</p>
		
		<p>
			<button type="submit"><?php _e('Update profile'); ?></button>
		</p>
		<?php osc_runHook('user_form'); ?>
	</div>
</div>
</form>
