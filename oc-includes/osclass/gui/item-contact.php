<div id="home_header"><div><?php _e('Contact seller'); ?></div></div>

<form action="item.php" method="post" onsubmit="return validate_contact();">
<input type="hidden" name="action" value="contact_post" />
<input type="hidden" name="id" value="<?php echo $item['id']; ?>" />

<div align="center">
	<div id="register_form" style="width: 410px; margin-bottom: 20px; padding: 10px;" align="left">
		<table width="100%">
		<tr>
			<td><?php _e('To (seller)'); ?></td>
			<td><?php echo $item['contact_name']; ?></td>
		</tr>
		<tr>
			<td><?php _e('Item'); ?></td>
			<td><a href="<?php echo createItemURL($item); ?>"><?php echo $item['title']; ?></a></td>
		</tr>
		<tr>
			<td><label for="yourName"><?php _e('Your name'); ?></label> <?php _e('(optional)'); ?></td>
			<td><input type="text" name="yourName" id="yourName" /></td>
		</tr>
		<tr>
			<td><label for="yourEmail"><?php _e('Your email address'); ?></label></td>
			<td><input type="text" name="yourEmail" id="yourEmail" /></td>
		</tr>
		<tr>
			<td><label for="phoneNumber"><?php _e('Phone number'); ?></label> <?php _e('(optional)'); ?></td>
			<td><input type="text" name="phoneNumber" id="phoneNumber" /></td>
		</tr>
		<tr>
			<td><label for="message"><?php _e('Message'); ?></label></td>
			<td><textarea cols="50" name="message" id="message"></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><button type="submit"><?php _e('Send message'); ?></button></td>
		</tr>
		</table>
	</div>
</div>
</form>
w
