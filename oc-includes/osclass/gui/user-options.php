<div id="home_header"><div><?php _e('Options'); ?></div></div>
<form action="<?php echo osc_createURL('user');?>" method="post">
<?php UserForm::location_javascript(); ?>
<input type="hidden" name="action" value="options_post" />

<div align="left">
	<div id="options_form" style="width: 400px; margin-bottom: 20px;">
		<p>
		<input type="checkbox" name="show_phone" value=1 <?php echo (isset($user_prefs['show_phone']) && $user_prefs['show_phone']==1)?'checked':''; ?>/><label for="name"><?php _e('Show phone-number'); ?></label><br />
        <?php _e('Show the phone-number on the ads you posted. Users will be able to contact you by phone.'); ?>
		</p>

        <p>
			<button type="submit"><?php _e('Save options'); ?></button>
		</p>		
	</div>
</div>
</form>
