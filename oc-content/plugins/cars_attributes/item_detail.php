
<h3><?php _e('Cars attributes') ; ?></h3>

<table>
<tr>
	<td><label><?php echo __('Make'); ?></label></td>
	<td><label><?php echo  $detail['s_make']; ?></td>
</tr>
<tr>
	<td><label><?php echo __('Model'); ?></label></td>
	<td><label><?php echo  $detail['s_model']; ?></td>
</tr>
<tr>
<?php $locales = Locale::newInstance()->listAllEnabled();
if(count($locales)==1) {
$locale = $locales[0];?>
<p>
<label><?php echo __('Car type'); ?></label><br />
<?php echo @$detail['locale'][$locale['pk_c_code']]['s_car_type']; ?>
</p>

<?php } else { ?>
<div class="tabber">
<?php foreach($locales as $locale) {?>
<div class="tabbertab">
<h2><?php echo $locale['s_name']; ?></h2>

<p>
<label><?php echo __('Car type'); ?></label><br />
<?php echo @$detail['locale'][$locale['pk_c_code']]['s_car_type']; ?>
</p>

</div>
<?php }; ?>
</div>
<?php }; ?>
</tr>
<tr>
	<td><label><?php echo __('Year'); ?></label></td>
	<td><label><?php echo  $detail['i_year']; ?></td>
</tr>
<tr>
	<td><label><?php echo __('Doors'); ?></label></td>
	<td><label><?php echo  $detail['i_doors']; ?></td>
</tr>
<tr>
	<td><label><?php echo __('Seats'); ?></label></td>
	<td><label><?php echo  $detail['i_seats']; ?></td>
</tr>
<tr>
	<td><label><?php echo __('Mileage'); ?></div>
</label></td>
		<td><label><?php echo  $detail['i_mileage']; ?></td>
</tr>
<tr>
	<td><label><?php echo __('Engine size (cc)'); ?></label></td>
		<td><label><?php echo  $detail['i_engine_size']; ?></td>
</tr>
<tr>
	<td><label><?php echo __('Num. Airbags'); ?></label></td>
	<td><label><?php echo  $detail['i_num_airbags']; ?></td>
</tr>
<tr>
	<td><label><?php echo __('Transmission'); ?></label></td>
	<td><label><?php echo  $detail['e_transmission']; ?></td>
</tr>
<tr>
	<td><label><?php echo __('Fuel'); ?></label></td>
	<td><label><?php echo  $detail['e_fuel']; ?></td>
</tr>
<tr>
	<td><label><?php echo __('Seller'); ?></label></td>
	<td><label><?php echo  $detail['e_seller']; ?></td>
</tr>
<tr>
    <td>
    <label><?php echo __('Warranty'); ?>: </label><?php echo $detail['b_warranty'] ? '<strong>'.__('Yes').'</strong>' : __('No'); ?><br />
    <label><?php echo __('New'); ?>: </label><?php echo $detail['b_new'] ? '<strong>'.__('Yes').'</strong>' : __('No'); ?><br />
	</td>
</tr>
<tr>
	<td><label><?php echo __('Power'); ?></label></td>
	<td><label><?php echo  $detail['i_power']; ?></label><label><?php echo  $detail['e_power_unit']; ?></label></td>
</tr>
<tr>
	<td><label><?php echo __('Gears'); ?></label></td>
	<td><label><?php echo  $detail['i_gears']; ?></label>
    </td>
</tr>
</table>

