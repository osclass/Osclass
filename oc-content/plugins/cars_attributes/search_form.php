
<h3><?php _e('Cars attributes') ; ?></h3>

<table>
<tr>
	<td><label for="type"><?php echo __('Type'); ?></label></td>
	<td><input type="text" name="type" id="type" value="" /></td>
</tr>
<tr>
	<td><label for="model"><?php echo __('Model'); ?></label></td>
	<td><input type="text" name="model" id="model" value="" /></td>
</tr>
<tr>
	<td><label for="numAirbags"><?php echo __('Num. of airbags'); ?></label></td>
	<td>
	<select name="numAirbags" id="numAirbags">
		<?php foreach(range(0, 8) as $n): ?>
			<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
		<?php endforeach; ?>
	</select>
	</td>
</tr>
<tr>
	<td><label for="transmission"><?php echo __('Transmission'); ?></label></td>
	<td>
		<input type="radio" name="transmission" value="MANUAL" id="manual" /> <label for="manual"><?php echo __('Manual'); ?></label><br />
		<input type="radio" name="transmission" value="AUTO" id="auto" /> <label for="auto"><?php echo __('Automatic'); ?></label><br />
	</td>
</tr>
</table>

