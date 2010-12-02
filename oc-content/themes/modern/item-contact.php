<div id="home_header"><div><?php _e('Contact seller'); ?></div></div>
<?php ContactForm::js_validation(); ?>
<form action="item.php" method="post" onsubmit="return validate_contact();">
<input type="hidden" name="action" value="contact_post" />
<input type="hidden" name="id" value="<?php echo $item['pk_i_id']; ?>" />

<div align="center">
	<div id="register_form" style="width: 410px; margin-bottom: 20px; padding: 10px;" align="left">
		<table width="100%">
		<tr>
			<td><?php _e('To (seller)'); ?></td>
			<td><?php echo $item['s_contact_name']; ?></td>
		</tr>
		<tr>
			<td><?php _e('Item'); ?></td>
			<td><a href="<?php echo osc_createItemURL($item); ?>"><?php echo $item['s_title']; ?></a></td>
		</tr>
		<tr>
			<td><label for="yourName"><?php _e('Your name'); ?></label> <?php _e('(optional)'); ?></td>
			<td><?php ContactForm::your_name(); ?></td>
		</tr>
		<tr>
			<td><label for="yourEmail"><?php _e('Your email address'); ?></label></td>
			<td><?php ContactForm::your_email(); ?></td>
		</tr>
		<tr>
			<td><label for="phoneNumber"><?php _e('Phone number'); ?></label> <?php _e('(optional)'); ?></td>
			<td><?php ContactForm::your_phone_number(); ?></td>
		</tr>
		<tr>
			<td><label for="message"><?php _e('Message'); ?></label></td>
			<td><?php ContactForm::your_message(); ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><button type="submit"><?php _e('Send message'); ?></button></td>
		</tr>
		</table>
	</div>
</div>
</form>
