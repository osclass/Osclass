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

		<label for="email"><?php _e('E-mail'); ?></label><br />
        <?php echo $user['s_email']; ?><br />
        <a href="<?php echo osc_createURL(array('file' =>'user', 'action' => 'change_email'));?>" ><?php _e('Modify e-mail');?></a> <a href="<?php echo osc_createURL(array('file' =>'user', 'action' => 'change_password'));?>" ><?php _e('Modify password');?></a>
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
		<label for="webSite"><?php _e('Web site'); ?></label><br />
        <?php UserForm::website_text($user); ?>
		</p>
		
		<p>
		<label for="info"><?php _e('Additional information'); ?></label><br />
        <?php UserForm::info_textarea($user); ?>
		</p>
		

		<p>
			<button type="submit"><?php _e('Update profile'); ?></button>
		</p>
		<?php osc_runHook('user_form'); ?>
        <div style="float:right;"><a onclick="javascript:return confirm('<?php echo __('WARNING: This will also delete the items and comments related to you. This action can not be undone. Are you sure you want to continue?'); ?>')" href="<?php echo osc_createURL(array('file'=>'user', 'action'=>'delete_user'));?>"><?php echo __('Delete my user'); ?></a></div>
        <div style="clear:both;"></div>
	</div>
</div>
</form>
