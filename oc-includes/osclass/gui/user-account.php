    <div class="account_info">
        <h2><?php _e('Your account'); ?></h2>
    	<p>
    	    <strong><?php _e('Name'); ?>:</strong> <?php echo $user['s_name']; ?><br />
        	<strong><?php _e('User name'); ?>:</strong> <?php echo $user['s_username']; ?><br />
        	<strong><?php _e('E-mail'); ?>:</strong> <?php echo $user['s_email']; ?><br />
        	<strong><?php _e('Web site'); ?>:</strong> <?php echo $user['s_website']; ?>
    	</p>
    	<h3><?php _e('Additional information'); ?>:</h3>
        <p>
            <?php echo $user['s_info']; ?>
        	<strong><?php _e('Mobile phone'); ?>:</strong> <?php echo $user['s_phone_mobile']; ?><br />
        	<strong><?php _e('Land phone'); ?>:</strong> <?php echo $user['s_phone_land']; ?>
        </p>
    
        <strong><a href="<?php echo osc_createProfileURL(); ?>" ><?php _e('Edit your profile'); ?></a></strong>
    </div>
    
    <!-- Close .content & #main open at user-menu.php -->
    </div>
</div>