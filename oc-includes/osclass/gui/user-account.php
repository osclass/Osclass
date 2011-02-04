<div id="account_items">
    <h1><?php _e('Latest items'); ?></h1>

    <?php if(isset($items) && count($items)>0) {
    foreach($items as $i) { ?>
	<div class="userItem" >
		<div><a href="<?php osc_create_item_url($i, true); ?>"><?php echo $i['s_title']; ?></a></div>

		<div class="userItemData" >
		<?php _e('Publication date'); ?>: <?php echo osc_formatDate($i); ?><br />
		<?php _e('Price'); ?>: <?php echo osc_format_price($i) ; ?>
		</div>

		<div class="userItemButtons" ><a onclick="javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?'); ?>')" href="<?php echo osc_createURL(array('file' => 'user', 'action' => 'deleteItem', 'id' => $i['pk_i_id'], 'secret' => $i['s_secret']));?>"><?php _e('Delete'); ?></a> | <a href="<?php echo osc_createURL(array('file' => 'user', 'action' => 'editItem', 'id' => $i['pk_i_id'], 'secret' => $i['s_secret']));?>"><?php _e('Edit'); ?></a></div>
	</div>
	<br />
    <?php };
    } else {
        _e('You do not have any items yet.') ;
    };
    ?>
</div>
<div id="account_contact">
    <h1><?php _e('Contact form') ; ?></h1>

    <form action="<?php echo ABS_WEB_URL;?>user.php" method="post">
    <input type="hidden" name="action" value="contact_post" />

    <table>
    <tr>
	    <td><label for="subject"><?php _e('Subject'); ?></label></td>
	    <td><?php ContactForm::the_subject(); ?></td>
    </tr>
    <tr>
        <td><label for="message"><?php _e('Message'); ?></label></td>
        <td><?php ContactForm::your_message(); ?></td>
    </tr>
    <tr>
	    <td colspan="2" style="text-align: right;"><input type="submit" value="<?php _e('Send'); ?>" /></td>
    </tr>
    </table>

    </form>
</div>
