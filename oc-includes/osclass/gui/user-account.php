<div id="home_header"><div><?php _e('Your account'); ?></div></div>

<div align="center">
	<div id="register_form" style="text-align:left; width: 400px; margin-bottom: 20px;">
		<p>
		<label for="name"><?php _e('Name'); ?>: </label>
        <?php echo $user['s_name']; ?>
		</p>
		
		<p>
		<label for="userName"><?php _e('User name'); ?>: </label>
        <?php echo $user['s_username']; ?>
		</p>
		
		<p>
		<label for="email"><?php _e('E-mail'); ?>: </label>
        <?php echo $user['s_email']; ?>
		</p>
		
		<p>
		<label for="webSite"><?php _e('Web site'); ?>: </label>
        <?php echo $user['s_website']; ?>
		</p>
		
		<p>
		<label for="info"><?php _e('Additional information'); ?>: </label><br />
        <?php echo $user['s_info']; ?>
		</p>
		
		<p>
		<label for="phoneMobile"><?php _e('Mobile phone'); ?>: </label>
        <?php echo $user['s_phone_mobile']; ?>
		</p>
		
		<p>
		<label for="phoneLand"><?php _e('Land phone'); ?>: </label>
        <?php echo $user['s_phone_land']; ?>
		</p>
		
	</div>
</div>
