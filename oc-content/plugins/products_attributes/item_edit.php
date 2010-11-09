<h3><?php _e('Realestate attributes') ; ?></h3>
<table>
    <tr>
	<td><input type="text" name="make" id="make" value="<?php echo $detail['s_make']; ?>" size="20" /></td>
        <td><label for="make"><?php _e('Make'); ?></label></td>
    </tr>
    <tr>
        <td><label for="model"><?php _e('Model'); ?></label></td>
        <td><input type="text" name="model" id="model" value="<?php echo $detail['s_model']; ?>" size="20" /></td>
    </tr>
</table>